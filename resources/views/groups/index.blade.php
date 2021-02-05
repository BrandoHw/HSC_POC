@extends('layouts.app')

@section('style')
<style>
#groupTable input {
  border-radius: 5px;
}
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
<div class="col-md-12">
    <!-- Display alert -->
    @if ($message = Session::get('success'))

        <div class="alert alert-success alert-dismissible" group="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="alert-message">
                <strong>Hello there!</strong> {{ $message }}
            </div>
        </div>
    @endif

    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Groups</strong> Management</h3>
        </div>
        <div class="col-auto ml-auto text-right mt-n1">
            @can('group-create')
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#createGroupModal" onClick="getCreateGroupInfo()">
                    <span class="align-middle">Remove</span>
                </button>
            @endcan
        </div>
	</div>
    
    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover nowrap" id="groupTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col" style="width:25%">Name</th>
                                    <th scope="col" style="width:20%">Users No.</th>
                                    <th scope="col" style="width:20%">Tags No.</th>
                                    <th scope="col" style="width:20%">Project No.</th>
                                    <th scope="col" style="width:10%"class="noSort">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groups as $group)
                                    <tr id="trGroup-{{ $group->id }}">
                                        <td>{{ $group->id }}</td>
                                        <td id="tdGroupName-{{ $group->id }}">
                                            <a href="{{ route('groups.show',$group->id) }}">
                                                {{ $group->name }} 
                                            </a>
                                        </td>
                                        <td id="tdGroupUsersNum-{{ $group->id }}" >
                                            @svg('user', 'feather-user align-middle') 
                                            <span class="align-middle">{{ $group->users->count() }}</span>
                                        </td>
                                        <td id="tdGroupTagsNum-{{ $group->id }}" >
                                            @svg('tablet', 'feather-tablet align-middle') 
                                            <span class="align-middle">{{ $group->tags->count() }}</span>
                                        </td>
                                        <td id="tdGroupProjectsNum-{{ $group->id }}" >
                                            @svg('grid', 'feather-grid align-middle') 
                                            <span class="align-middle">{{ $group->projects->count() }}</span>
                                        </td>
                                        <td class="table-action row" style="margin:0px">
                                            @can('group-edit')
                                                <a href="{{ route('groups.show',$group->id) }}">
                                                    @svg('edit-2', 'feather-edit-2 align-middle')  
                                                </a>
                                            @endcan
                                            @can('group-delete')
                                                <a href="#" id="{{ $group->id }}" onClick="deleteGroup(this.id)">
                                                    @svg("trash", "feather-trash align-middle")
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Create Group Modal -->
    <div class="modal fade" id="createGroupModal" tabindex="-1" group="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" group="document" style="heigh:400px; overflow:auto;">
            @include("groups.create")
        </div>
    </div>
</div>
</div>
@endsection 

@section('script')
<script>
    var groupTable;
    var projectTable;
    var displayProjectTable;

    var projectsNull;
    var projects_selected;

    /* Stepper */
    var stepperEle = $('.bs-stepper')[0];
    var stepper = new Stepper(stepperEle);

    $(function () {

        /* Group DataTable */
        groupTable = $('#groupTable').DataTable({
            dom: '<fl<t>ip>',
            responsive: true,
            stateSave: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            orders: [],
            columnDefs: [{
                targets  : 'noSort',
                orderable: false,
            }, {
                className: 'control',
                orderable: false,
                targets: 0
            }]
        })

        /* Project DataTable */
		projectTable = $('#projectTable').DataTable({
			dom: '<fl<t>ip>',
			columnDefs: [{
				targets: 0,
				checkboxes: {
					'selectRow': true
				},
				orderable: false,
			}],
			select: {
				style: 'multi',
			},
			order: [[1, 'asc']],
			scrollY: 400,
			scrollCollapse: true,
			paging:true,
			scroller: true,
		})

        /* Display Selected Reader Table */
		displayProjectTable = $('#displayProjectTable').DataTable({
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

        stepperEle.addEventListener('shown.bs-stepper', function(){
			projectTable.columns.adjust().draw();
		});

        $('#nextBtn-1').on('click', function(){
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            createGroupFormAlert('remove', false, null);

            var data = getData(true, null);

            $.ajax({
                url: "{{ route('groups.store') }}",
                type: "POST",
                data: data,
                success: function(response){
                    if($.isEmptyObject(response['success'])){
                        var errors = response['errors'];

                        Object.keys(errors).forEach(function(key){
                            switch(key) {
                                case 'name':
                                    createGroupFormAlert('name', false, errors[key][0]);
                                    break;
                                case 'detail':
                                    createGroupFormAlert('detail', false, errors[key][0]);
                                    break;
                                default:
                                    createGroupFormAlert('other', false, errors[key][0]);
                            }
                        })
                    }
                    else{
                        stepper.next()
                        createGroupFormAlert('remove', false, null);
                    }
                },
                error: function(error){
                    console.log(error);
                }
            })
        })

        $('#nextBtn-2').on('click', function(){
            /* Project DataTable */
            projects_selected = projectTable.column(0).checkboxes.selected();

            selectProjectTableAlert('remove');

            if(_.isEmpty(projects_selected)){
                selectProjectTableAlert('show');
            }
            else{
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var projects_id = [];
                $.each(projects_selected, function(index, value){
                    projects_id.push(value);
                });
                
                var data = getData(false, projects_id);

                $.ajax({
                    url: "{{ route('groups.store') }}",
                    type: "POST",
                    data: data,
                    success: function(response){
                        var group = response['group'];
                        var id = group['id'];
                        projectsNull = response['projectsNull'];

                        $('#displayName').val(group['name']);
                        $('#displayDetail').val(group['detail']);

                        group['projects'].forEach(function(item){
                            this.row.add([
                                item['id'],
                                item['name'],
                                item['start_date'],
                                item['end_date']
                            ])
                            .node().id = 'trFinalProject-' + item['id'];
                            this.draw(false);
                        }, displayProjectTable);

                        groupTable.row.add([
                            id,
                            '<a href="{{ route("groups.index") }}/'+ id +'" >' + group['name'] + '</a>',
                            '@svg("user", "feather-user align-middle")'
                                + '<span class="align-middle">0</span>',
                            '@svg("tablet", "feather-tablet align-middle")'
                                + '<span class="align-middle">0</span>',
                            '@svg("grid", "feather-grid align-middle")'
                                + '<span class="align-middle">' + group['projects'].length + '</span>',
                            '<a href="{{ route("groups.index") }}/'+ id +'">@svg("edit-2", "feather-edit-2 align-middle")</a>'
                                + '<a href="#" id="'+ id + '" onClick="deleteGroup(this.id)">@svg("trash", "feather-trash align-middle")</a>',
                        ])
                        .node().id = 'trGroup-' + id;
                        groupTable.draw(false);
                        $('#trGroup-'+id+' td:eq(1)').attr('id', 'tdGroupName-'+id);
                        $('#trGroup-'+id+' td:eq(2)').attr('id', 'tdGroupUsersNum-'+id);
                        $('#trGroup-'+id+' td:eq(3)').attr('id', 'tdGroupTagsNum-'+id); 
                        $('#trGroup-'+id+' td:eq(4)').attr('id', 'tdGroupProjectsNum-'+id); 
                        $('#trGroup-'+id+' td:eq(5)').attr('id', 'tdGroupAction-'+id); 
                        $('#trGroup-'+id+' td:eq(5)').attr('class', 'table-action row'); 
                        $('#trGroup-'+id+' td:eq(5)').css('margin', '0px'); 

                        stepper.next()
                        createGroupFormAlert('remove', false, null);
                    },
                    error: function(error){
                        console.log(error);
                    }
                })
            }
        })

        $('#previousBtn-2').on('click', function(){
            stepper.previous();
        })

        $('#previousBtn-3').on('click', function(){
            stepper.previous();
        })
    })
    function getCreateGroupInfo(){
        /* Remove all alerts */
        createGroupFormAlert('remove', true, null);
        selectProjectTableAlert('remove');

        /* Reset Stepper */
        stepper.to(1);

        /* Reset all tables */
        displayProjectTable.clear().draw();

        if(_.isEmpty(projectsNull)){
            projectTable.columns(0).checkboxes.deselectAll(true);
        }
        else{
            projectTable.clear().draw();
            projectsNull.forEach(function(item){
                this.row.add([
                    item['id'],
                    item['id'],
                    item['name'],
                    item['start_date'],
                    item['end_date']
                ])
                .node().id = 'trNullReader-' + item['id'];
                this.draw(false);
            }, projectTable)
        }
    }

    function deleteGroup(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: 'groups/' + id,
            type: "DELETE",
            data: {id: id},
            success:function(response){
                groupTable.rows('#trGroup-'+id).remove().draw();
                notyf.success(response['success']);
            },
            error:function(error){
                console.log(error)
            }
        })

    }

    /* Group Form Alert Display Function */
    function createGroupFormAlert(input, reset, message){
        switch(input){
            case 'remove':
                $('#groupName').css("border", "");
                $('#groupDetail').css("border", "");
                $('#groupNameAlert').remove();
                $('#groupDetailAlert').remove();
                $('#groupOtherAlert').remove();
                
                if(reset){
                    $('#groupName').val('');
                    $('#groupDetail').val('');
                }
                break;
            case 'name':
                $("#groupName").css("border", "1px solid red");
                $("#groupNameField")
                    .append('<div class="alert-danger" id="groupNameAlert">' + message + '</div>');
                break;
            case 'detail':
                $("#groupDetail").css("border", "1px solid red");
                $("#groupDetailField")
                    .append('<div class="alert-danger" id="groupNameAlert">' + message + '</div>');
                break;
            case 'other':
                $('#test-l-1')
                    .prepend('<div class="alert-danger" id="groupOtherAlert">' + message + '</div>');
        }
    };

    /* Project Table Alert Display Function */
    function selectProjectTableAlert(input){
        switch(input){
            case 'remove':
                $('#projectTableHolder').css("border", "");
                $('#selectProjectMessage').removeClass("text-danger font-weight-bold");
                break;
            case 'show':
                $('#projectTableHolder').css("border", "1px solid red");
                $('#selectProjectMessage').addClass("text-danger font-weight-bold");
                break;
        }
    };

    function getData(validate, projects_id_array){
        if(validate){
            return {
                validate: true,
                name: $('#groupName').val(),
                detail: $('#groupDetail').val(),
            }
        }
        else{
            return {
                name: $('#groupName').val(),
                detail: $('#groupDetail').val(),
                projects_id: projects_id_array,
            }
        }
    }

    
</script>
@endsection