@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3 justify-content-start">
        <a href="{{ route('companies.index') }}" style="padding-left: 12px">
            @svg('chevron-left', 'feather-chevron-left align-middle')  
        </a>
        <h3 style="padding-left: 12px">Company: <strong>{{ $company->name }}</strong></h3>
    </div>

    <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Company Details</h5>
                </div>
                <div class="card-body text-center">
                    <img src="img/avatars/avatar-4.jpg" alt="Christina Mason" class="img-fluid rounded-circle mb-2" width="128" height="128">
                    <h5 class="card-title mb-0">Christina Mason</h5>
                    <div class="text-muted mb-2">Lead Developer</div>

                    <div>
                        <a class="btn btn-primary btn-sm" href="#">Follow</a>
                        <a class="btn btn-primary btn-sm" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg> Message</a>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <h5 class="h6 card-title">Skills</h5>
                    <a href="#" class="badge bg-primary mr-1 my-1">HTML</a>
                    <a href="#" class="badge bg-primary mr-1 my-1">JavaScript</a>
                    <a href="#" class="badge bg-primary mr-1 my-1">Sass</a>
                    <a href="#" class="badge bg-primary mr-1 my-1">Angular</a>
                    <a href="#" class="badge bg-primary mr-1 my-1">Vue</a>
                    <a href="#" class="badge bg-primary mr-1 my-1">React</a>
                    <a href="#" class="badge bg-primary mr-1 my-1">Redux</a>
                    <a href="#" class="badge bg-primary mr-1 my-1">UI</a>
                    <a href="#" class="badge bg-primary mr-1 my-1">UX</a>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <h5 class="h6 card-title">About</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home feather-sm mr-1"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Lives in <a href="#">San Francisco, SA</a>
                        </li>

                        <li class="mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase feather-sm mr-1"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg> Works at <a href="#">GitHub</a></li>
                        <li class="mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin feather-sm mr-1"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> From <a href="#">Boston</a></li>
                    </ul>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <h5 class="h6 card-title">Elsewhere</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1"><span class="fas fa-globe fa-fw mr-1"></span> <a href="#">staciehall.co</a></li>
                        <li class="mb-1"><span class="fab fa-twitter fa-fw mr-1"></span> <a href="#">Twitter</a></li>
                        <li class="mb-1"><span class="fab fa-facebook fa-fw mr-1"></span> <a href="#">Facebook</a></li>
                        <li class="mb-1"><span class="fab fa-instagram fa-fw mr-1"></span> <a href="#">Instagram</a></li>
                        <li class="mb-1"><span class="fab fa-linkedin fa-fw mr-1"></span> <a href="#">LinkedIn</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-xl-9">
            <div class="card">
                <div class="card-header">
					<h5 class="card-title"><strong>{{ $company->name }}</strong></h5>
					<h6 class="card-subtitle text-muted">Showing readers that are assigned to this company.</h6>
				</div>
				<div class="card-body">
					<table class="table table-striped table-sm" id="companyReaderTable">
						<thead>
							<tr>
								<th scope="col" style="width:5%">#</th>
								<th scope="col" style="width:20%">Serial</th>
								<th scope="col" style="width:20%">Mac Address</th>
								<th scope="col" style="width:20%">Building</th>
								<th scope="col" style="width:25%">Floor</th>
								<th class="align-middle no-sort" scope="col" style="width:10%">Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach($company->buildings as $building)
								@foreach($building->readers as $reader)
									<tr id="trReader-{{ $reader->id }}">
										<td>
											{{ $reader->id }}
										</td>
										<td id="tdReaderSerial-{{ $reader->id }}">
											<a href="{{ route('readers.show',$reader->id) }}">
												{{ $reader->serial }} 
											</a>
										</td>
										<td id="tdReaderMacAdd-{{ $reader->id }}">{{ $reader->mac_addr }}</td>
										<td id="tdReaderBuilding-{{ $reader->id }}">{{ $reader->floor->building->name }}</td>
										<td id="tdReaderFloor-{{ $reader->id }}">{{ $reader->floor->number }}</td>
										<td id="tdReaderAction-{{ $reader->id }}" class="table-action" style="margin:0px">
											<a href="#" id="{{ $reader->id }}" onClick="editReader(this.id)">
												@svg('edit-2', 'feather-edit-2 align-middle')  
											</a>
											<a href="#" id="{{ $reader->id }}" onClick="deleteReader(this.id)">
												@svg("trash", "feather-trash align-middle")
											</a>
										</td>
									</tr>
								@endforeach
							@endforeach
						</tbody>
					</table>
				</div>
            </div>
            <!-- Assign New Reader Modal -->
            <div class="modal fade" id="assignNewReaderModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Assign <strong>New</strong> Reader</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body m-3" id="assignModalBody">
                            <div id="stepper" class="bs-stepper linear">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step active" data-target="#test-l-1">
                                    <button type="button" class="step-trigger" role="tab" id="steppertrigger1" aria-controls="test-l-1" aria-selected="true">
                                        <span class="bs-stepper-circle">1</span>
                                        <span class="bs-stepper-label">Select Readers</span>
                                    </button>
                                    </div>
                                    <div class="bs-stepper-line"></div>
                                    <div class="step" data-target="#test-l-2">
                                    <button type="button" class="step-trigger" role="tab" id="steppertrigger2" aria-controls="test-l-2" aria-selected="false" disabled="disabled">
                                        <span class="bs-stepper-circle">2</span>
                                        <span class="bs-stepper-label">Choose Location</span>
                                    </button>
                                    </div>
                                    <div class="bs-stepper-line"></div>
                                    <div class="step" data-target="#test-l-3">
                                    <button type="button" class="step-trigger" role="tab" id="steppertrigger3" aria-controls="test-l-3" aria-selected="false" disabled="disabled">
                                        <span class="bs-stepper-circle">3</span>
                                        <span class="bs-stepper-label">Done</span>
                                    </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="test-l-1" role="tabpanel" class="bs-stepper-pane active dstepper-block" aria-labelledby="steppertrigger1">
                                        <p class="text-left" id="selectReaderMessage">
                                            <span style="color: red; display:block; float:left"> *</span>
                                            <em>Please select at least one reader to proceed.</em>
                                        </p>
                                        <div class="table-responsive" id="readerTableHolder">
                                            <table class="table table-striped table-bordered table-hover table-sm" id="readerTable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" style="width:10%"></th>
                                                        <th scope="col" >#</th>
                                                        <th scope="col" >Name</th>
                                                        <th scope="col" >Mac Address</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($readersNull as $reader)
                                                    <tr id="trNullReader-{{ $reader->id }}">
                                                        <td style="text-align: center" >{{ $reader->id }}</td>
                                                        <td >{{ $reader->id }}</td>
                                                        <td  id="trNullReaderSerial-{{ $reader->id }}">{{ $reader->serial }}</td>
                                                        <td  id="trNullReaderMacAdd-{{ $reader->id }}">{{ $reader->mac_addr }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <button style="float:right" class="btn btn-primary" id="nextBtn-1">Next</button>
                                    </div>
                                    <div id="test-l-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="steppertrigger2">
                                        <p class="text-left" id="selectLocationMessage">
                                            <span style="color: red; display:block; float:left"> *</span>
                                            <em>Please assign location to these readers.</em>
                                        </p>
                                        <div class="table-responsive" id="selectedReaderTableHolder">
                                            <table class="table table-striped table-bordered table-hover table-sm" id="selectedReaderTable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" style="width:5%">#</th>
                                                        <th scope="col" style="width:16%">Serial</th>
                                                        <th scope="col" style="width:20%">Mac Address</th>
                                                        <th scope="col" style="width:27%">Building</th>
                                                        <th scope="col" style="width:33%">Floor</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div style="float:right">
                                            <button class="btn btn-secondary" id="previousBtn-2">Back</button>
                                            <button type="submit" class="btn btn-primary" id="nextBtn-2">Save</button>
                                        </div>
                                    </div>
                                    <div id="test-l-3" role="tabpanel" class="bs-stepper-pane text-center" aria-labelledby="steppertrigger3">
                                        <div class="alert alert-success" role="alert">
                                            <div class="alert-message">
                                                @svg('check-circle', 'feather-check-circle align-middle')
                                                <strong>Readers</strong> Added Successfully!
                                            </div>
                                        </div>
                                        <table class="table table-striped table-bordered table-hover table-sm" id="displaySelectedReaderTable">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="width:5%">#</th>
                                                    <th scope="col" style="width:16%">Serial</th>
                                                    <th scope="col" style="width:20%">Mac Address</th>
                                                    <th scope="col" style="width:27%" class="noSort">Building</th>
                                                    <th scope="col" style="width:33%" class="noSort">Floor</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div style="float:right">
                                            <button class="btn btn-secondary mt-5" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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


    $(function () {
		
		/* Company Reader DataTable */
		companyReaderTable = $('#companyReaderTable').DataTable({
			dom: 'frBtip',
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

		$('#assignNewReaderModal').on('shown.bs.modal', function(){
			readerTable.columns.adjust().draw();
		});

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
		
		/* Stepper */
		var stepper = new Stepper($('.bs-stepper')[0]);

		var readers_selected = null;
		var readers = null;

		$('#nextBtn-1').on('click', function(){
			/* Reader DataTable */
			readers_selected = readerTable.column(0).checkboxes.selected();
			readers = [];

			$('.selectedReader').remove();
			$('#readerTableHolder').css("border", "");
			$('#selectReaderMessage').removeClass("text-danger font-weight-bold");

			if(_.isEmpty(readers_selected)){
				$('#readerTableHolder').css("border", "1px solid red");
				$('#selectReaderMessage').addClass("text-danger font-weight-bold");
			}
			else{
				stepper.next();

				$.each(readers_selected, function(index, value){
					var index = readerTable.rows().eq( 0 ).filter( function (rowIdx) {
						return readerTable.cell( rowIdx, 0 ).data() === value ? true : false;
					} )[0];

					var data = readerTable.rows(index).data()[0];
					data = {
						id: data[0],
						serial: data[2],
						mac_addr: data[3]
					}
					readers.push(data);

					$('#assignNewReaderForm').append(
						$('<input>')
							.attr('type', 'hidden')
							.attr('name', 'readers_id[]')
							.attr('class', 'selectedReader')
							.attr('id', 'reader-' + value)
							.val(value)
					);
				})

				readers.forEach(function (item){
					var buildingSelector = '<select name="building_id_' + item['id'] +'" class="form-control" id="' + item['id'] + '"onChange="renderFloor(this.id)">'
						+'<option value="" >Please select a building...</option>';
						+'</select>';
					
					var floorSelector = '<select name="floor_id_' + item['id'] + '" class="form-control" id=floor-' + item['id'] + '>'
						+'<option value="" >Please select a building first...</option>';
						+'</select>';
					this.row.add([
						item['id'],
						item['serial'],
						item['mac_addr'],
						buildingSelector,
						floorSelector,
					])
					.node().id = 'trSelectedReader-' + item['id'];
					this.draw(false);

					@foreach($company->buildings as $building)
						var building = @json($building);
						$('#' + item['id']).append($('<option></option>')
							.attr("value", building['id'])
							.text(building['name']));
					@endforeach
				}, selectedReaderTable);
				
				selectedReaderTable.columns.adjust().draw();
			}
		})

		$('#nextBtn-2').on('click', function(){
			var error = false;

			readers.forEach(function(item){
				var buildingSelector = $('#' + item['id']);
				var floorSelector = $('#floor-' + item['id']);
				var message = $('#selectLocationMessage');

				/* Reset their style */
				buildingSelector.css("border", "");
				floorSelector.css("border", "");
				message.removeClass("text-danger font-weight-bold");

				if(_.isEmpty(floorSelector.val())){
					if(_.isEmpty(buildingSelector.val())){
						buildingSelector.css("border", "1px solid red");
					}
					floorSelector.css("border", "1px solid red");
					message.addClass("text-danger font-weight-bold");
					error = true
				}
			})

			if(!error){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				
				readers.forEach(function (item){
					item['buildingId'] = $('#' + item['id']).val();
					item['floorId'] = $('#floor-' + item['id']).val();
				});

				var data = {
					companyId: @json($company)['id'],
					readers: readers
				}

				$.ajax({
					url: '{{ route("companies.readers.store", $company->id) }}',
					type: "POST",
					data: data,
					success:function(response){
						stepper.next();

						// Reset table data
						displaySelectedReaderTable.clear().draw();
						
						readers.forEach(function (item){
							item['buildingId'] = $('#' + item['id']).val();
							item['buildingName'] = $('#' + item['id']).find('option:selected').text();
							item['floorId'] = $('#floor-' + item['id']).val();
							item['floorName'] = $('#floor-' + item['id']).find('option:selected').text();

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
						
						displaySelectedReaderTable.columns.adjust().draw();

						readers.forEach(function(item){
							var id = item['id'];
							this.row.add([
								id,
								'<a href="{{ route("readers.show", $reader->id) }}">' + item['serial'] +'</a>',
								item['mac_add'],
								item['buildingName'],
								item['floorName'],
								'<a href="#" id="{{ $reader->id }}" onClick="editReader(this.id)">@svg("edit-2", "feather-edit-2 align-middle")</a>'
									+ '<a href="#" id="{{ $reader->id }}" onClick="deleteReader(this.id)">@svg("trash", "feather-trash align-middle")</a>',
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

						},companyReaderTable);

						notyf.success(response['success']);
						
						// Reset variables data
						readersNull = response['readersNull'];
						readers_selected = null;
						readers = null;
						
						// Reset tables data
						selectedReaderTable.clear().draw();
					},
					error:function(error){
						console.log(error)
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
	
	function renderFloor(id){
		var buildingId = $('#' + id).val();

		var select = $('#floor-' + id);
		select.empty();

		if(buildingId == null){
			select.append('<option value="">' + 'Please select a building first...' + '</option>');
		}
		else{
			var url = '{{ url("buildings") }}/' + $('#' + id).val();
	
			$.get(url, function(response) {
	
				if(typeof response['floors'] === 'undefined'){
					select.append('<option value="">' + 'Please select a building first...' + '</option>');
				}
				else{
					select.append('<option value="">' + 'Please select a floor...' + '</option>');
					$.each(response['floors'],function(key, value) {
						select.append('<option value=' + key + '>' + value + '</option>');
					});
				}
				
			});
		}
	}

	function editReader(id){
		var buildingSelector = '<select name="building_id_' + id +'" class="form-control" id="' + id + '"onChange="renderFloor(this.id)">'
			+'<option value="" >Please select a building...</option>';
			+'</select>';
					
		var floorSelector = '<select name="floor_id_' + id + '" class="form-control" id=floor-' + id + '>'
			+'<option value="" >Please select a building first...</option>';
			+'</select>';
		
		var actionGroup = '<a href="#" style="color:green !important" id="' + id + '" onClick="saveChanges(this.id)">@svg("check", "feather-check align-middle")</a>'
				+ '<a href="#" style="color:red !important" id="' + id + '" onClick="cancelChanges(this.id)">@svg("x", "feather-x align-middle")</a>'

		$('#tdReaderBuilding-'+id).html(buildingSelector);
		$('#tdReaderFloor-'+id).html(floorSelector);
		$('#tdReaderAction-'+id).html(actionGroup);

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			url: '{{ route("companies.readers.index", $company->id) }}' + '/' + id + '/edit',
			type: "GET",
			success:function(response){
				console.log(response);

				var buildingSelector = $('#' + response['reader']['id']);
				var floorSelector = $('#floor-' + response['reader']['id']);
				
				$.each(response['buildings'], function(key, value){
					buildingSelector.append($('<option></option>')
						.attr("value", value['id'])
						.text(value['name']));
				})
				buildingSelector.val(response['readerBuilding']['id']);

				floorSelector.empty();
				floorSelector.append('<option value="">' + 'Please select a floor...' + '</option>');
				$.each(response['buildingFloors'],function(key, value) {
					floorSelector.append('<option value=' + value['id'] + '>' + value['number'] + '</option>');
				});
				floorSelector.val(response['readerFloor']['id']);
				
			},
			error:function(error){
				console.log(error)
			}
		})
	}

	function saveChanges(id){
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		var data = {
			id: id,
			floorId: $('#floor-'+id).val()
		};

		console.log(data);
		$.ajax({
			url: '{{ route("companies.readers.index", $company->id) }}' + '/' + id,
			type: "PATCH",
			data: data,
			success:function(response){
				console.log(response);

				var actionGroup = '<a href="#">@svg("edit-2", "feather-edit-2 align-middle")</a>'
					+ '<a href="#" id="{{ $reader->id }}" onClick="deleteReader(this.id)">@svg("trash", "feather-trash align-middle")</a>'

				$('#tdReaderBuilding-'+id).html(response['building']['name']);
				$('#tdReaderFloor-'+id).html(response['floor']['number']);
				$('#tdReaderAction-'+id).html(actionGroup);

				notyf.success(response['success']);
				
			},
			error:function(error){
				console.log(error)
			}
		})
	}

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
				console.log(response);

				companyReaderTable
					.rows('#trReader-'+response['reader']['id'])
					.remove()
					.draw();

				notyf.success(response['success']);
				
				readersNull = response['readersNull'];
			},
			error:function(error){
				console.log(error)
			}
		})
	}
</script>
@endsection