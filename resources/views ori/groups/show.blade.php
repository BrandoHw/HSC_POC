@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3 justify-content-start">
        <a href="{{ route('groups.index') }}" style="padding-left: 12px">
            @svg('chevron-left', 'feather-chevron-left align-middle')  
        </a>
        <h3 style="padding-left: 12px">Group: <strong>{{ $group->name }}</strong></h3>
    </div>

    <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card mb-3">
                <div class="card-header">
					<div class=" h5 card-title mb-0 row">
						<span style="padding-left: 12px"><strong>Group Details</strong></span>
						<a href="#" style="padding-left: 0.5em">
							@svg('edit', 'feather-edit align-middle')  
						</a>
					</div>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('img/group-default.png') }}" alt="Christina Mason" class="img-fluid rounded-circle mb-2" width="128" height="128">
                    <h5 class="card-title mb-0">{{ $group->name }}</h5>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <!-- <h5 class="h6 card-title">Projects</h5> -->
                    <div class=" h5 card-title row">
						<span style="padding-left: 12px">Projects</span>
						<a href="#" style="padding-left: 0.5em">
							@svg('edit', 'feather-edit align-middle')  
						</a>
					</div>
                    @foreach($group->projects as $project)
                        <a href="#" class="badge badge-dark bg-primary mr-1 my-1">{{ $project->name }}</a>
                    @endforeach
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <h5 class="h6 card-title">About</h5>
                    <div>
						@if(empty($group->detail))
							<font color='gray'><em>No description</em></font>
						@else
							{{ $group->detail }}
						@endif
					</div>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-xl-9">
            
            @include('groups.users.index')
            @include('groups.timeblocks.show')

            <!-- Assign New User Modal -->
            <div class="modal fade" id="assignNewUserModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    @include('groups.users.create')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>

    var groupUserTable;
    var userTable;
    var selectedUserTable;
    var displaySelectedUserTable;

    var usersNull;
    var users_selected;
    var users = [];
    var previousSelectedTags = [];

    /* Stepper */
    var stepperEle = $('.bs-stepper')[0];
    var stepper = new Stepper(stepperEle);

    $(function(){

        /* Group User Table */
        groupUserTable = $('#groupUserTable').DataTable({
			dom: '<fB<t>ip>',
			buttons: {
				buttons: [{
					text: '@svg("plus", "feather-plus align-middle")'
						+ '<span class="align-middle">Assign new user</span>',
					className: 'btn-primary',
					id: 'assignNewBuildingsBtn',
					action: function ( e, dt, node, config ) {
						$('#assignNewUserModal').modal('show');
					}
				}],
				dom: {
					button: {
						className: 'btn'
					}
				}
			},
			columnDefs: [{
				targets: 'noSort',
				orderable: false,
			}],
			order: [[ 0, 'asc' ]],
			paging: false,
			info: false,
		})

        /* User DataTable */
		userTable = $('#userTable').DataTable({
			dom: '<fl<t>ip>',
            ajax: {
                url: '{{ route("groups.users.create", $group->id) }}',
                dataSrc: 'usersNull'
            },
            columns:[
                {data: 'id'},
                {data: 'id'},
                {data: 'name'},
                {data: 'role'},
            ],
			columnDefs: [{
				targets: 0,
				checkboxes: {
					'selectRow': true
				},
				orderable: false,
			}],
            rowId: 'rowId',
			select: {
				style: 'multi',
			},
			order: [[1, 'asc']],
			scrollY: 400,
			scrollCollapse: true,
			paging:true,
			scroller: true,
		})

        /* Selected User Table */
		selectedUserTable = $('#selectedUserTable').DataTable({
			dom: '<fl<t>ip>',
			columnDefs: [{
				targets: 'noSort',
				orderable: false,
			}],
			order: [[ 0, 'asc' ]],
			paging: false,
			searching: false,
			info: false,
		})

        /* Display Selected User Table */
		displaySelectedUserTable = $('#displaySelectedUserTable').DataTable({
			dom: '<fl<t>ip>',
			columnDefs: [{
				targets: 'noSort',
				orderable: false,
			}],
			order: [[ 0, 'asc' ]],
			paging: false,
			searching: false,
			info: false,
		})

        $('#assignNewUserModal').on('shown.bs.modal', function(){
			userTable.columns.adjust().draw();
		});
        
        $('#assignNewUserModal').on('hidden.bs.modal', function(){
            /* Reload the user datatable data */
            userTable.ajax.reload();

			/* Remove all alerts */
            selectUserTableAlert('remove');

            /* Deselect checkboxes */
            userTable.columns(0).checkboxes.deselectAll(true);

            /* Reset Stepper */
            stepper.to(1);

            /* Reset variable */
            users = [];
            previousSelectedTags = [];

            /* Reset all tables */
            selectedUserTable.clear().draw();
            displaySelectedUserTable.clear().draw();
		});

        $('#nextBtn-1').on('click', function(){
            users_selected = userTable.column(0).checkboxes.selected();
            
            selectUserTableAlert('remove');

            if(_.isEmpty(users_selected)){
                selectUserTableAlert('show');
            }
            else{
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route("groups.users.create", $group->id) }}',
                    type: "GET",
                    success:function(response){
                        selectedUserTable.clear().draw();

                        var tagsNull = response['tagsNull'];

                        users = [];

                        $.each(users_selected, function(index, value){
                            var data = userTable.rows('#trNullUser-'+value).data()[0];
                            users.push({
                                id: data['id'],
                                name: data['name'],
                                role: data['role'],
                            });
                        });

                        var thisArg = {
                            table: selectedUserTable,
                            tagsNull: tagsNull
                        }

                        users.forEach(function (item){
                            this['table'].row.add([
                                item['id'],
                                item['name'],
                                item['role'],
                                generateTagSelector(item['id'])
                            ])
                            .node().id = 'trSelectedUser-' + item['id'];
                            this['table'].draw(false);
                            $('#trSelectedUser-'+item['id']+' td:eq(3)').attr('id', 'tdTagSelector-'+item['id']); 
                            
                            this['tagsNull'].forEach(function(item){
                                $('#tagSelector-' + this['id']).append($('<option></option>')
                                    .attr("value", item['id'])
                                    .text(item['serial']));
                            }, item);

                            $('#tagSelector-' + item['id']).select2({
                                width: 'resolve',
                                placeholder: 'Please select a tag...',
                                dropdownParent: $('#assignNewUserModal'),
                            });
                        }, thisArg);

                        stepper.next();
                    },
                    error:function(error){
                        console.log(error);
                    }
                })
            }
        })

        $('#nextBtn-2').on('click', function(){

            selectTagAlert('remove', false);
            var errors = selectTagAlert('show', true);

            if(!errors){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                users.forEach(function(item){
                    item['tagId'] = $('#tagSelector-' + item['id']).val();
                    item['tagName'] = $('#tagSelector-' + item['id']).find('option:selected').text();
                })

                var data = {
                    users: users
                }

                $.ajax({
                    url: '{{ route("groups.users.store", $group->id) }}',
                    type: "POST",
                    data: data,
                    success:function(response){

                        users.forEach(function(item){
                            this.row.add([
                                item['id'],
                                item['name'], 
                                item['role'], 
                                item['tagName']
                            ])
                            .node().id = 'trFinalUser-' + item['id'];
							this.draw(false);
                        }, displaySelectedUserTable);

                        users.forEach(function(item){
                            var id = item['id'];
                            this.row.add([
                                id,
                                '<a href="#", id="'+ id +'">' + item['name'] +'</a>',
                                item['role'],
                                item['tagName'],
                                generateActionGroup('edit/delete', id)
                            ])
                            .node().id = 'trUser-' + item['id'];
							this.draw(false);
							$('#trUser-'+id+' td:eq(1)').attr('id', 'tdUserName-'+id);
							$('#trUser-'+id+' td:eq(2)').attr('id', 'tdUserRole-'+id);
							$('#trUser-'+id+' td:eq(3)').attr('id', 'tdUserTag-'+id); 
							$('#trUser-'+id+' td:eq(4)').attr('id', 'tdUserAction-'+id); 
							$('#trUser-'+id+' td:eq(4)').attr('class', 'table-action row'); 
							$('#trUser-'+id+' td:eq(4)').css('margin', '0px');

                        }, groupUserTable)

                        stepper.next();

						notyf.success(response['success']);

                    },
                    error:function(error){
                        console.log(error);
                    }
                })
            }

            console.log('complete');
        });

        $('#previousBtn-2').on('click', function(){
            stepper.previous();
        });
    })

    function selectUserTableAlert(input){
        switch(input){
            case 'remove':
                $('#userTableHolder').css("border", "");
                $('#selectUserMessage').removeClass("text-danger font-weight-bold");
                break;
            case 'show':
                $('#userTableHolder').css("border", "1px solid red");
                $('#selectUserMessage').addClass("text-danger font-weight-bold");
                break;
        }
    }

    function selectTagAlert(input, returnValue){
        var errors = false;

        users.forEach(function(item){
            var tagSelectorHolder = $('#tdTagSelector-' + item['id']);
            var value = $('#tagSelector-' + item['id']).val();
            var message = $('#selectTagMessage');

            switch(input){
                case 'remove':
                    tagSelectorHolder.find('span.select2-selection').css("border", "");
                    message.removeClass("text-danger font-weight-bold");
                    break;
                case 'show':
                    if(_.isEmpty(value)){
                        tagSelectorHolder.find('span.select2-selection').css("border", "1px solid red");
                        message.addClass("text-danger font-weight-bold");
                        errors = true;
                    }
                    break;
            }
        })

        if(returnValue){
            return errors;
        }
    }

    function generateTagSelector(id){
        return '<select class="form-control" style="width:100%" id="tagSelector-' + id + '"onChange="disableTagOption(this.id)">'
            +'<option></option>'
            +'</select>';
    }

    function disableTagOption(selectorId){
        var value = $('#'+selectorId).val();
        var id = selectorId.split('-')[1];

        users.forEach(function(item){
            console.log(item);
            if(item['id'] != id){
                if(previousSelectedTags[id] != value){
                    console.log(previousSelectedTags[id]);
                    $('#tagSelector-' + item['id'] +' option[value=' + previousSelectedTags[id] + ']').removeAttr('disabled');
                }
                $('#tagSelector-' + item['id'] +' option[value=' + value + ']').attr('disabled', 'disabled');
            }
        })
        
        previousSelectedTags[id] = value;
    }

    function generateActionGroup(input, id){
        switch(input){
            case 'edit/delete':
                return '<a href="#" id="'+ id +'" onClick="editUser(this.id)">@svg("edit-2", "feather-edit-2 align-middle")</a>'
                    + '<a href="#" id="'+ id +'" onClick="deleteUser(this.id)">@svg("trash", "feather-trash align-middle")</a>'
                break;
            case 'save/cancel':
                return '<a href="#" style="color:green !important" id="' + id + '" onClick="saveChanges(this.id)">@svg("check", "feather-check align-middle")</a>'
                    + '<a href="#" style="color:red !important" id="' + id + '" onClick="cancelChanges(this.id)">@svg("x", "feather-x align-middle")</a>'
                break;
        }
    }

    function saveChanges(id){
        $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

        var currentId = parseInt(id);
        var user = users.find( ({ id }) => id === currentId );
        var data = {
            user: user,
            tagId: $('#tagSelector-'+id).val(),
        }

        $.ajax({
			url: '{{ route("groups.users.index", $group->id) }}/' + id,
			type: "PATCH",
            data: data,
			success:function(response){
                var user = response['user'];
                $('#tdUserTag-' + user['id']).html(user['tag']['serial']);
                $('#tdUserAction-' + user['id']).html(generateActionGroup('edit/delete', user['id']));

                users = users.filter(function( obj ) {
                    return obj['id'] !== currentId;
                });

                notyf.success(response['success']);
            },
            error:function(error){
                console.log(error);
            }
        })
    }

    function cancelChanges(id){
        var currentId = parseInt(id);
        var user = users.find( ({ id }) => id === currentId );
        $('#tdUserTag-' + user['id']).html(user['tag']['serial']);
        $('#tdUserAction-' + user['id']).html(generateActionGroup('edit/delete', user['id']));

        users = users.filter(function( obj ) {
            return obj['id'] !== currentId;
        });
    }

    /* Edit User */
	function editUser(id){
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			url: '{{ route("groups.users.index", $group->id) }}/' + id + '/edit',
			type: "GET",
			success:function(response){
                var user = response['user'];
                users.push(user);

				var tagsNull = response['tagsNull'];

                /* Replace with tag selector */
                $('#tdUserTag-' + user['id']).html(generateTagSelector(user['id']));
                tagsNull.forEach(function(item) {
                    $('#tagSelector-' + this['id']).append($('<option></option>')
                        .attr("value", item['id'])
                        .text(item['serial']));
                }, user)

                $('#tagSelector-' + user['id']).select2({
                    width: 'resolve',
                    placeholder: 'Please select a tag...',
                });

                $('#tagSelector-' + user['id']).val(user['tag']['id']).trigger('change');

                /* Replace action group */
                $('#tdUserAction-' + user['id']).html(generateActionGroup('save/cancel', user['id']));

			},
			error:function(error){
				console.log(error)
			}
		});
	}
    /* Delete User */
	function deleteUser(id){
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			url: '{{ route("groups.users.index", $group->id) }}' + '/' + id,
			type: "DELETE",
			success:function(response){
				console.log(response);

				groupUserTable
					.rows('#trUser-'+response['user']['id'])
					.remove()
					.draw();

				notyf.success(response['success']);

                userTable.ajax.reload();
			},
			error:function(error){
				console.log(error)
			}
		})
	}
</script>
<script type="text/javascript">
   
   //This function is basically brute force creating datetimepickers for each empty cell
   //in the table, if the granularity of the timeblocks are decreased (currently at 30 mins), then 
   //the end condition for the for loop must be increased

   $(document).on("click",".timepicker", function() {
        var thisId = $(this).attr("id");
        // console.log(thisId);
    });

    $(function () {
        for (i = 0; i < 200; i++){
            var startID = "#start-".concat(i);
            var endID = "#end-".concat(i);
            // console.log(endID);
            $(startID).datetimepicker({
                stepping: 30,
                format: 'LT',
                format: 'HH:mm',
                useCurrent: false,
            });

            $(endID).datetimepicker({
                stepping: 30,
                format: 'LT',
                format: 'HH:mm',
                useCurrent: false,
            });
        }
    });

    function saveTimeblock(timeblock){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var id = timeblock.split('-')[1];

        var data = {
            schedule_id: $('#createSchedule-' + id).val(),
            day: $('#createDay-' + id).val(),
            company_id: $('#createCompany-' + id).val(),
            building_id: $('#createBuilding-' + id).val(),
            start_time: $('#createStartTime-' + id).val(),
            end_time: $('#createEndTime-' + id).val(),
        }

        console.log(data);

        $.ajax({
            url: '{{ route("groups.timeblocks.store", $group->id) }}',
            type: "POST",
            data: data,
            success:function(response){
                console.log(response);
                if($.isEmptyObject(response['success'])){
                    var errors = response['errors'];

                    Object.keys(errors).forEach(function(key){
                        switch(key) {
                            case 'name':
                                // createGroupFormAlert('name', false, errors[key][0]);
                                break;
                            case 'detail':
                                // createGroupFormAlert('detail', false, errors[key][0]);
                                break;
                            default:
                                notyf.error(errors[key][0]);
                        }
                    })
                }else{
                    location.reload();
                }
            },
            error:function(error){
                console.log(error);
            }
        })
    }   
    function deleteTimeblock(timeblock){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var id = timeblock.split('-')[1];

        console.log(id);

        $.ajax({
            url: '{{ route("groups.timeblocks.index", $group->id) }}/' + id,
            type: "DELETE",
            success:function(response){
                console.log(response);
                    location.reload();
            },
            error:function(error){
                console.log(error['responseJSON']);
            }
        })
    }   
    
</script>

@endsection