@extends('layouts.app')

@section('style')
<style>
    .pac-container { z-index: 100000; }
</style>
@endsection


@section('content')
<div class="container-fluid p-0">
    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3 justify-content-start">
        <a href="{{ route('companies.index') }}" style="padding-left: 12px">
            @svg('chevron-left', 'feather-chevron-left align-middle')  
        </a>
        <h3 style="padding-left: 12px">Client: <strong>{{ $company->name }}</strong></h3>
    </div>

    <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card mb-3">
                <div class="card-header">
					<div class=" h5 card-title mb-0 row">
						<span style="padding-left: 12px"><strong>Client Details</strong></span>
						<!-- <a href="#" style="padding-left: 0.5em">
							@svg('edit', 'feather-edit align-middle')  
						</a> -->
					</div>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('img/company-default.png') }}" alt="Christina Mason" class="img-fluid rounded-circle mb-2" width="128" height="128">
                    <h5 class="card-title mb-0">{{ $company->name }}</h5>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <h5 class="h6 card-title">About</h5>
                    <div>
						@if(empty($company->detail))
							<font color='gray'><em>No description</em></font>
						@else
							{{ $company->detail }}
						@endif
					</div>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-xl-9">
            
            @include('companies.buildings.index')
            @include('companies.readers.index')

            <!-- Assign New Building Modal -->
            <div class="modal fade" id="assignNewBuildingModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    @include('companies.buildings.create')
                </div>
            </div>
            <!-- Assign New Reader Modal -->
            <div class="modal fade" id="assignNewReaderModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    @include('companies.readers.create')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=drawing,places&callback=initMap" async bdefer ></script>
<script src="/js/mapInput.js"></script>
<script type="text/javascript">

	var companyReaderTable;
	var readerTable;
	var selectedReaderTable;
	var displaySelectedReaderTable;

	var readersNull;
	var readers_selected;
	var readers = [];

	/* Stepper */
    var stepperEle = $('.bs-stepper')[0];
    var stepper = new Stepper(stepperEle);

    $(function () {
		/* Company Building DataTable */
		companyBuildingTable = $('#companyBuildingTable').DataTable({
			dom: '<fB<t>ip>',
			buttons: {
				buttons: [{
					text: '@svg("plus", "feather-plus align-middle")'
						+ '<span class="align-middle">Assign new building</span>',
					className: 'btn-primary',
					id: 'assignNewBuildingsBtn',
					action: function ( e, dt, node, config ) {
						$('#assignNewBuildingModal').modal('show');
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
			info: false
		})

		/* Company Reader DataTable */
		companyReaderTable = $('#companyReaderTable').DataTable({
			dom: '<fB<t>ip>',
			buttons: {
				buttons: [{
					text: '@svg("plus", "feather-plus align-middle")'
						+ '<span class="align-middle">Assign new reader</span>',
					className: 'btn-primary',
					id: 'assignNewReadersBtn',
					action: function ( e, dt, node, config ) {
						$('#assignNewReaderModal').modal('show');
						stepper.to(1);
						if(_.isEmpty(readersNull)){
							readerTable.columns(0).checkboxes.deselectAll(true);
						}
						else{
							readerTable.data().clear();

							readersNull.forEach(function(item){
								this.row.add([
									item['id'],
									item['id'],
									item['serial'],
									item['mac_addr'],
								])
								.node().id = 'trNullReader-' + item['id'];
								this.draw(false);
							}, readerTable);
						}

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
			info: false
		})

		/* Reader DataTable */
		readerTable = $('#readerTable').DataTable({
			dom: '<fl<t>ip>',
			ajax: {
                url: '{{ route("companies.readers.create", $company->id) }}',
                dataSrc: 'readersNull'
            },
            columns:[
                {data: 'id'},
                {data: 'id'},
                {data: 'serial'},
                {data: 'mac_addr'},
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

		

		/* Selected Reader Table */
		selectedReaderTable = $('#selectedReaderTable').DataTable({
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
		
		/* Display Selected Reader Table */
		displaySelectedReaderTable = $('#displaySelectedReaderTable').DataTable({
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

		/* Reset building form */
        $('#assignNewBuildingModal').on('hidden.bs.modal', function(){
			/* Remove all alerts */
            createBuildingFormAlert('remove', true, null);
		});

		/* Realign reader table header */
		$('#assignNewReaderModal').on('shown.bs.modal', function(){
			readerTable.columns.adjust().draw();
		});
		
		/* Reset reader assignment */
        $('#assignNewReaderModal').on('hidden.bs.modal', function(){
            /* Reload the reader datatable data */
            readerTable.ajax.reload();

			/* Remove all alerts */
            selectReaderTableAlert('remove');

            /* Deselect checkboxes */
            readerTable.columns(0).checkboxes.deselectAll(true);

            /* Reset Stepper */
            stepper.to(1);

            /* Reset variable */
            readers = [];

            /* Reset all tables */
            selectedReaderTable.clear().draw();
            displaySelectedReaderTable.clear().draw();
		});

		$('#nextBtn-1').on('click', function(){
			/* Reader DataTable */
			readers_selected = readerTable.column(0).checkboxes.selected();

			selectReaderTableAlert('remove');

			if(_.isEmpty(readers_selected)){
				selectReaderTableAlert('show');
			}
			else{
				$.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route("companies.readers.create", $company->id) }}',
                    type: "GET",
                    success:function(response){
						selectedReaderTable.clear().draw();

						var buildings = response['company']['buildings'];

						readers = [];

						$.each(readers_selected, function(index, value){
							var data = readerTable.rows('#trNullReader-'+value).data()[0];
							readers.push({
								id: data['id'],
								serial: data['serial'],
								mac_addr: data['mac_addr'],
							})
						});

						var thisArg = {
                            table: selectedReaderTable,
                            buildings: buildings
                        }

						readers.forEach(function (item){
                            this['table'].row.add([
                                item['id'],
                                item['serial'],
                                item['mac_addr'],
                                generateBuildingSelector(item['id']),
                                generateFloorSelector(item['id']),
                            ])
                            .node().id = 'trSelectedReader-' + item['id'];
                            this['table'].draw(false);
							$('#trSelectedReader-'+item['id']+' td:eq(3)').attr('id', 'tdBuildingSelector-'+item['id']); 
							$('#trSelectedReader-'+item['id']+' td:eq(4)').attr('id', 'tdFloorSelector-'+item['id']); 
                            
                            this['buildings'].forEach(function(item){
                                $('#buildingSelector-' + this['id']).append($('<option></option>')
                                    .attr("value", item['id'])
                                    .text(item['name']));
                            }, item);

                            $('#buildingSelector-' + item['id']).select2({
                                width: 'resolve',
                                placeholder: 'Please select a tag...',
                                dropdownParent: $('#assignNewReaderModal'),
                            });

                            $('#floorSelector-' + item['id']).select2({
                                width: 'resolve',
                                placeholder: 'Please select a building first...',
                                dropdownParent: $('#assignNewReaderModal'),
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
			selectLocationAlert('remove', false);
			var errors = selectLocationAlert('show', true);

			console.log(errors);

			if(!errors){
				$.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                readers.forEach(function(item){
                    item['buildingId'] = $('#buildingSelector-' + item['id']).val();
                    item['buildingName'] = $('#buildingSelector-' + item['id']).find('option:selected').text();
                    item['floorId'] = $('#floorSelector-' + item['id']).val();
                    item['floorName'] = $('#floorSelector-' + item['id']).find('option:selected').text();
                })

                var data = {
                    readers: readers
                }

                $.ajax({
                    url: '{{ route("companies.readers.store", $company->id) }}',
                    type: "POST",
                    data: data,
                    success:function(response){

						readers.forEach(function(item){
                            this.row.add([
                                item['id'],
                                item['serial'], 
                                item['mac_addr'], 
                                item['buildingName'],
                                item['floorName'],
                            ])
                            .node().id = 'trFinalReader-' + item['id'];
							this.draw(false);
                        }, displaySelectedReaderTable);

						readers.forEach(function(item){
                            var id = item['id'];
                            this.row.add([
                                id,
                                '<a href="#", id="reader-'+ id +'">' + item['serial'] +'</a>',
                                item['mac_addr'],
                                item['buildingName'],
                                item['floorName'],
                                generateActionGroup('edit/delete', id),
                            ])
                            .node().id = 'trReader-' + item['id'];
							this.draw(false);
							$('#trReader-'+id+' td:eq(1)').attr('id', 'tdReaderSerial-'+id);
							$('#trReader-'+id+' td:eq(2)').attr('id', 'tdReaderMacAdd-'+id);
							$('#trReader-'+id+' td:eq(3)').attr('id', 'tdReaderBuilding-'+id); 
							$('#trReader-'+id+' td:eq(4)').attr('id', 'tdReaderFloor-'+id); 
							$('#trReader-'+id+' td:eq(5)').attr('id', 'tdReaderAction-'+id); 
							$('#trReader-'+id+' td:eq(5)').attr('class', 'table-action row'); 
							$('#trReader-'+id+' td:eq(5)').css('margin', '0px');

                        }, companyReaderTable)

                        stepper.next();

						notyf.success(response['success']);
					},
					error:function(error){
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

	/* Building Form Alert Controller */
	function createBuildingFormAlert(input, reset, message){
		switch(input){
			case 'remove': 
				$('#name').css('border', '');
				$('#detail').css('border', '');
				$('#floor_num').css('border', '');
				$('#address').css('border', '');
				$('#createBuildingNameAlert').remove();
				$('#createBuildingDetailAlert').remove();
				$('#createBuildingFloorAlert').remove();
				$('#createBuildingAddressAlert').remove();
				$('#createBuildingOtherAlert').remove();

				if(reset){
					$('#name').val('');
					$('#detail').val('');
					$('#floor_num').val('');
					$('#address').val('');
				}
				break;
			case 'name':
				$('#createBuildingNameField')
					.append('<div class="alert-danger" id="createBuildingNameAlert">' + message + '</div>');
				$("#name").css("border", "1px solid red");
				break;
			case 'detail':
				$('#createBuildingDetailField')
					.append('<div class="alert-danger" id="createBuildingDetailAlert">' + message + '</div>');
				$("#detail").css("border", "1px solid red");
				break;

			case 'floor_num':
				$('#createBuildingFloorField')
					.append('<div class="alert-danger" id="createBuildingFloorAlert">' + message + '</div>');
				$("#floor_num").css("border", "1px solid red");
				break;
			case 'lat':
			case 'lng':
			case 'address':
				$('#createBuildingAddressField')
					.append('<div class="alert-danger" id="createBuildingAddressAlert">' + message + '</div>');
				$("#address").css("border", "1px solid red");
				break;
			case 'other':
				$('#createBuildingModalBody')
					.prepend('<div class="alert-danger" id="createBuildingOtherAlert">' + message + '</div>');
		}
	}

	/* Select Reader Alert Controller */
	function selectReaderTableAlert(input){
        switch(input){
            case 'remove':
                $('#readerTableHolder').css("border", "");
                $('#selectReaderMessage').removeClass("text-danger font-weight-bold");
                break;
            case 'show':
                $('#readerTableHolder').css("border", "1px solid red");
                $('#selectReaderMessage').addClass("text-danger font-weight-bold");
                break;
        }
    }

	/* Select Location Alert Controller */
    function selectLocationAlert(input, returnValue){
        var errors = false;

        readers.forEach(function(item){
            var buildingSelectorHolder = $('#tdBuildingSelector-' + item['id']);
            var floorSelectorHolder = $('#tdFloorSelector-' + item['id']);
            var buildingValue = $('#buildingSelector-' + item['id']).val();
            var floorValue = $('#floorSelector-' + item['id']).val();
            var message = $('#selectLocationMessage');

            switch(input){
                case 'remove':
                    buildingSelectorHolder.find('span.select2-selection').css("border", "");
                    message.removeClass("text-danger font-weight-bold");
                    break;
                case 'show':
                    if(_.isEmpty(floorValue)){
                        floorSelectorHolder.find('span.select2-selection').css("border", "1px solid red");
						errors = true;
                    }
					if(_.isEmpty(buildingValue)){
						buildingSelectorHolder.find('span.select2-selection').css("border", "1px solid red");
						errors = true;
					}
					if(errors){
						message.addClass("text-danger font-weight-bold");
					}
                    break;
            }
        })

        if(returnValue){
            return errors;
        }
    }

	function generateBuildingSelector(id){
        return '<select class="form-control" style="width:100%" id="buildingSelector-' + id + '"onChange="changeFloor(this.id)">'
            +'<option></option>'
            +'</select>';
    }

	function generateFloorSelector(id){
        return '<select class="form-control" style="width:100%" id="floorSelector-' + id + '">'
            +'<option></option>'
            +'</select>';
    }

	function changeFloor(buildingSelector){
		var id = buildingSelector.split('-')[1];
		var floorSelector = $('#floorSelector-' + id);

		var buildingSelectorHolder = $('#tdBuildingSelector-' + id);
		buildingSelectorHolder.find('span.select2-selection').css("border", "");

		var floorSelectorHolder = $('#tdFloorSelector-' + id);
		floorSelectorHolder.find('span.select2-selection').css("border", "");
		
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			url: '{{ route("buildings.index") }}/' + $('#buildingSelector-' + id).val(),
			type: "GET",
			success:function(response){
				var floors = response['floors'];

				floorSelector.empty();

				floors.forEach(function(item){
					floorSelector.append($('<option></option>')
						.attr("value", item['id'])
						.text(item['number']));
				})

			},
			error:function(error){
				console.log(error);
			}
		})
		
	}

	function generateActionGroup(input, id){
        switch(input){
            case 'edit/delete':
                return '<a href="#" id="'+ id +'" onClick="editReader(this.id)">@svg("edit-2", "feather-edit-2 align-middle")</a>'
                    + '<a href="#" id="'+ id +'" onClick="deleteReader(this.id)">@svg("trash", "feather-trash align-middle")</a>'
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
        var reader = readers.find( ({ id }) => id === currentId );
        var data = {
            reader: reader,
            floorId: $('#floorSelector-'+id).val(),
        }

		$.ajax({
			url: '{{ route("companies.readers.index", $company->id) }}' + '/' + id,
			type: "PATCH",
			data: data,
			success:function(response){

				var reader = response['reader'];

				$('#tdReaderBuilding-' + reader['id']).html(reader['building']['name']);
				$('#tdReaderFloor-' + reader['id']).html(reader['floor']['number']);
                $('#tdReaderAction-' + reader['id']).html(generateActionGroup('edit/delete', reader['id']));

                readers = readers.filter(function( obj ) {
                    return obj['id'] !== currentId;
                });

                notyf.success(response['success']);
			},
			error:function(error){
				console.log(error)
			}
		})
	}

	function cancelChanges(id){
		var building = $('#'+id).find('option:selected').text();
		var floor = $('#floor-'+id).find('option:selected').text();
		var actionGroup = '<a href="#" id="'+ id +'" onClick="editReader(this.id)">@svg("edit-2", "feather-edit-2 align-middle")</a>'
			+ '<a href="#" id="'+ id +'" onClick="deleteReader(this.id)">@svg("trash", "feather-trash align-middle")</a>';
		
		$('#tdReaderBuilding-'+id).html(building);
		$('#tdReaderFloor-'+id).html(floor);
		$('#tdReaderAction-'+id).html(actionGroup);
	}

	/* Edit Reader */
	function editReader(id){
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			url: '{{ route("companies.readers.index", $company->id) }}' + '/' + id + '/edit',
			type: "GET",
			success:function(response){

				var reader = response['reader'];
				readers.push(reader);

				var buildings = response['buildings'];
				var floors = response['floors'];

				/* Replace with building selector */
				$('#tdReaderBuilding-' + reader['id']).html(generateBuildingSelector(reader['id']));
				buildings.forEach(function(item) {
                    $('#buildingSelector-' + this['id']).append($('<option></option>')
                        .attr("value", item['id'])
                        .text(item['name']));
                }, reader)

                $('#buildingSelector-' + reader['id']).select2({
                    width: 'resolve',
                    placeholder: 'Please select a building...',
                });

                $('#buildingSelector-' + reader['id']).val(reader['building']['id']).trigger('change');
				
				/* Replace with floor selector */
				$('#tdReaderFloor-' + reader['id']).html(generateFloorSelector(reader['id']));
				floors.forEach(function(item) {
                    $('#floorSelector-' + this['id']).append($('<option></option>')
                        .attr("value", item['id'])
                        .text(item['number']));
                }, reader)

                $('#floorSelector-' + reader['id']).select2({
                    width: 'resolve',
                    placeholder: 'Please select a floor...',
                });

                $('#floorSelector-' + reader['id']).val(reader['floor']['id']).trigger('change');

                /* Replace action group */
                $('#tdReaderAction-' + reader['id']).html(generateActionGroup('save/cancel', reader['id']));

			},
			error:function(error){
				console.log(error)
			}
		})
	}

	/* Delete Reader */
	function deleteReader(id){
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			url: '{{ route("companies.readers.index", $company->id) }}' + '/' + id,
			type: "DELETE",
			success:function(response){

				companyReaderTable
					.rows('#trReader-'+response['reader']['id'])
					.remove()
					.draw();

				notyf.success(response['success']);
			},
			error:function(error){
				console.log(error)
			}
		})
	}

	/* Create Building */
	function createBuilding(){
        $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

			$('#name').css('border', '');
			$('#detail').css('border', '');
			$('#floor_num').css('border', '');
			$('#address').css('border', '');
            $('#createBuildingNameAlert').remove();
            $('#createBuildingDetailAlert').remove();
            $('#createBuildingFloorAlert').remove();
            $('#createBuildingAddressAlert').remove();
            $('#createBuildingOtherAlert').remove();
            
		var data = {
			company_id: @json($company->id),
			name: $('#name').val(),
			detail: $('#detail').val(),
			floor_num: $('#floor_num').val(),
			address: $('#address').val(),
			lat: $('#address_latitude').val(),
			lng: $('#address_longitude').val(),
		};

		$.ajax({
			url: '{{ route("buildings.store") }}',
			type: "POST",
			data: data,
			success:function(response){
                if($.isEmptyObject(response['success'])){
                    var errors = response['errors'];

                    Object.keys(errors).forEach(function(key){
                        switch(key) {
                            case 'name':
                                $('#createBuildingNameField')
                                    .append('<div class="alert-danger" id="createBuildingNameAlert">' + errors[key][0] + '</div>');
                                $("#name").css("border", "1px solid red");
                                break;

                            case 'detail':
                                $('#createBuildingDetailField')
                                    .append('<div class="alert-danger" id="createBuildingDetailAlert">' + errors[key][0] + '</div>');
                                $("#detail").css("border", "1px solid red");
                                break;

                            case 'floor_num':
                                $('#createBuildingFloorField')
                                    .append('<div class="alert-danger" id="createBuildingFloorAlert">' + errors[key][0] + '</div>');
                                $("#floor_num").css("border", "1px solid red");
                                break;
                            case 'lat':
                            case 'lng':
                            case 'address':
                                $('#createBuildingAddressField')
                                    .append('<div class="alert-danger" id="createBuildingAddressAlert">' + errors[key][0] + '</div>');
                                $("#address").css("border", "1px solid red");
                                break;
                            default:
                                $('#createBuildingModalBody')
                                    .prepend('<div class="alert-danger" id="createBuildingOtherAlert">' + errors[key][0] + '</div>');
                        }
                     })
                    
                } else {
                    notyf.success(response['success']);

                    var building = response['building'];
                    var id = building['id'];

                    companyBuildingTable.row.add([
                        id,
                        building['name'],
                        building['address'],
                        building['floor_num'],
						'<a href="#" id="'+ id + '" onClick="editBuilding(this.id)">@svg("edit-2", "feather-edit-2 align-middle")</a>'
						+ '<a href="#" id="'+ id + '" onClick="deleteBuilding(this.id)">@svg("trash", "feather-trash align-middle")</a>'
                    ]).node().id = 'trBuilding-' + id;
                    companyBuildingTable.draw(false);

                    $('#trBuilding-' + id +' td:eq(1)').attr('id', 'tdBuildingName-'+id);
                    $('#trBuilding-' + id +' td:eq(2)').attr('id', 'tdBuildingAddress-'+id);
                    $('#trBuilding-' + id +' td:eq(3)').attr('id', 'tdBuildingFloor-'+id); 
                    $('#trBuilding-' + id +' td:eq(4)').attr('id', 'tdBuildingAction-'+id); 
                    $('#trBuilding-' + id +' td:eq(4)').attr('class', 'table-action'); 
                    $('#trBuilding-' + id +' td:eq(4)').css('margin', '0px'); 

					$('#assignNewBuildingModal').modal('toggle');
                }
				
			},
			error:function(error){
                console.log(error)
			}
		})
    }

	/* Edit Building */
	function editBuilding(id){

	}

	/* Delete Building */
	function deleteBuilding(id){
        $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			url: '{{ route("buildings.index") }}' + '/' + id,
			type: "DELETE",
			success:function(response){

                companyBuildingTable
                    .rows('#trBuilding-'+ id)
                    .remove()
                    .draw();

				notyf.success(response['success']);
			},
			error:function(error){
				console.log(error)
			}
		})
    }
</script>

@endsection