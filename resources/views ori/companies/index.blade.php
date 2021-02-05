@extends('layouts.app')

@section('style')
<style>
    .pac-container { z-index: 100000; }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- Display alert -->
    @if ($message = Session::get('success'))

        <div class="alert alert-success alert-dismissible" company="alert">
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
            <h3><strong>Client</strong> Management</h3>
        </div>
        <div class="col-auto ml-auto text-right mt-n1">
            @can('company-create')
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCompanyModal" onClick="getCreateCompanyInfo()">
                    @svg('plus', 'feather-plus align-middle')  
                    <span class="align-middle">Add company</span>
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
                        <table class="table table-striped table-hover" id="companyTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col" style="width:45%">Name</th>
                                    <th scope="col" style="width:20%">Total Buildings #</th>
                                    <th scope="col" style="width:20%">Total Readers #</th>
                                    <th scope="col" style="width:10%" class="noSort">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companies as $company)
                                    <tr id="trCompany-{{ $company->id }}" style="cursor:pointer">
                                        <td class="company_id">{{ $company->id }}</td>
                                        <td>
                                            <a href="{{ route('companies.show',$company->id) }}">
                                                {{ $company->name }} 
                                            </a>
                                        </td>
                                        <td style="text-align:left">
                                            @svg('home', 'feather-home align-middle') 
                                            <span class="align-middle">{{ $company->buildings->count() }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $readersNum = 0;
                                                foreach($company->buildings as $building){
                                                    $readersNum = $readersNum + $building->readers->count();
                                                }
                                            @endphp
                                            @svg('airplay', 'feather-airplay align-middle') 
                                            <span class="align-middle">{{ $readersNum }}</span>
                                        </td>
                                        <td class="table-action">
                                            @can('company-edit')
                                                <a href="{{ route('companies.show',$company->id) }}">
                                                    @svg('edit-2', 'feather-edit-2 align-middle')
                                                </a>
                                            @endcan
                                            @can('company-delete')
                                                <a href="#" id="{{ $company->id }}" onClick=deleteCompany(this.id)>
                                                    @svg('trash', 'feather-trash align-middle')
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
    <!-- Create Company Modal -->
    <div class="modal fade" id="createCompanyModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            @include('companies.create')
        </div>
    </div>
@endsection      

@section('script')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=drawing,places&callback=initMap" async bdefer ></script>
<script src="/js/mapInput.js"></script>
<script>
    var companyTable;
    var companyBuildingTable;
    var displayCompanyBuildingTable;
    var buildingsId;

    /* Stepper */
    var stepper = new Stepper($('.bs-stepper')[0]);

    $(function() {
        /* Company Datatable */
        companyTable = $('#companyTable').DataTable( {
            dom: '<fl<t>ip>',
            responsive: true,
            stateSave: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            columnDefs: [ {
                "targets"  : 'noSort',
                "orderable": false,
            }],
            order: [[0, 'asc']]
        });
        
        @can('company-create')
        /* Company Building Datatable */
        companyBuildingTable = $('#companyBuildingTable').DataTable({
			dom: 'frBtip',
			buttons: {
				buttons: [{
					text: '@svg("plus", "feather-plus align-middle")'
						+ '<span class="align-middle">Assign new building</span>',
					className: 'btn-primary',
					id: 'assignNewBuildingsBtn',
					action: function ( e, dt, node, config ) {
                        $('#companyBuildingTableHolder').css('border', '');
                        $('#addBuildingMessage').removeClass("text-danger font-weight-bold");

                        $('#name').val("");
                        $('#detail').val("");
                        $('#floor_num').val("");
                        $('#address').val("");
                        $('#address_latitude').val("");
                        $('#address_longitude').val("");

                        $('#buildingTable').collapse('hide');
                        $('#buildingForm').collapse('show');

                        
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

        /* Display Company Building Table */
		displayCompanyBuildingTable = $('#displayCompanyBuildingTable').DataTable({
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

        $('.quitBuildingCreate').on('click', function(){
            $('#buildingTable').collapse('show');
            $('#buildingForm').collapse('hide');
            $('#name').val("");
			$('#detail').val("");
			$('#floor_num').val("");
			$('#address').val("");
			$('#address_latitude').val("");
            $('#address_longitude').val("");
            $('#createBuildingNameAlert').remove();
            $('#createBuildingDetailAlert').remove();
            $('#createBuildingFloorAlert').remove();
            $('#createBuildingAddressAlert').remove();
            $('#createBuildingOtherAlert').remove();

        })
        
        $('#nextBtn-1').on('click', function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#companyName').css('border', '');
            $('#companyDetail').css('border', '');
            $('#companyNameAlert').remove();
            $('#companyDetailAlert').remove();
            $('#companyOtherAlert').remove();
            
            var data = {
                validate: true,
                name: $('#companyName').val(),
                detail: $('#companyDetail').val()
            };

            $.ajax({
                url: '{{ route("companies.store") }}',
                type: "POST",
                data: data,
                success:function(response){
                    if($.isEmptyObject(response['success'])){
                        var errors = response['errors'];

                        Object.keys(errors).forEach(function(key){
                            switch(key) {
                                case 'name':
                                    if(document.getElementById('companyNameAlert') == null){
                                        $('#createNameField')
                                            .append('<div class="alert-danger" id="companyNameAlert">' + errors[key][0] + '</div>');
                                        $("#companyName").css("border", "1px solid red");
                                    }
                                    break;
                                case 'detail':
                                    if(document.getElementById('companyDetailAlert') == null){
                                        $('#createDetailField')
                                            .append('<div class="alert-danger" id="companyDetailAlert">' + errors[key][0] + '</div>');
                                        $("#companyDetail").css("border", "1px solid red");
                                    }
                                    break;
                                default:
                                    if(document.getElementById('companyOtherAlert') == null){
                                        $('#createBuildingModalBody')
                                            .prepend('<div class="alert-danger" id="companyOtherAlert">' + errors[key][0] + '</div>');
                                    }
                                }
                        })
                        
                    } else {
                        stepper.next();
                        $('#companyName').css('border', '');
                        $('#companyDetail').css('border', '');
                        $('#companyNameAlert').remove();
                        $('#companyDetailAlert').remove();
                        $('#companyOtherAlert').remove();
                    }
                },
                error:function(error){
                    console.log(error)
                }
            });
        });

        $('#nextBtn-2').on('click', function(){
            if(_.isEmpty(buildingsId)){
                console.log('empty');
                $('#companyBuildingTableHolder').css('border', '1px solid red');
                $('#addBuildingMessage').addClass("text-danger font-weight-bold");
            }
            else{
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var data = {
                    name: $('#companyName').val(),
                    detail: $('#companyDetail').val(),
                    buildingsId: buildingsId,
                }
                console.log(data);

                $.ajax({
                    url: '{{ route("companies.store") }}',
                    type: "POST",
                    data: data,
                    success:function(response){
                        console.log(response);
                        notyf.success(response['success']);
                        var company = response['company'];
                        var id = company['id'];
                        var buildings = response['buildings'];
                        var buildingsNum = response['buildingsNum'];
                        var readersNum = response['readersNum'];

                        companyTable.row.add([
                            id,
                            '<a href="{{ route("companies.index") }}/'+ id +'">' + company['name'] +'</a>',
                            '@svg("home", "feather-home align-middle")'
                                + '<span class="align-middle">' + buildingsNum + '</span>',
                            '@svg("airplay", "feather-airplay align-middle")'
                                + '<span class="align-middle">' + readersNum + '</span>',
                            '<a href="{{ route("companies.index") }}/'+ id +'">@svg("edit-2", "feather-edit-2 align-middle")</a>'
                                + '<a href="#" id="'+ id + '" onClick="deleteCompany(this.id)">@svg("trash", "feather-trash align-middle")</a>',
                        ])
                        .node().id = 'trCompany-' + id;
                        companyTable.draw(false);
                        $('#trCompany-'+id+' td:eq(1)').attr('id', 'tdCompanyName-'+id);
                        $('#trCompany-'+id+' td:eq(2)').attr('id', 'tdCompanyBuildingsNum-'+id);
                        $('#trCompany-'+id+' td:eq(3)').attr('id', 'tdCompanyReadersNum-'+id); 
                        $('#trCompany-'+id+' td:eq(4)').attr('id', 'tdCompanyAction-'+id); 
                        $('#trCompany-'+id+' td:eq(4)').attr('class', 'table-action row'); 
                        $('#trCompany-'+id+' td:eq(4)').css('margin', '0px'); 

                        $("#completeMessage").html('@svg("check-circle", "feather-check-circle align-middle")'
                            + '<strong>'+ company['name'] +'</strong> Added Successfully!")');
                        
                        $('#displayName').val(company['name']);
                        $('#displayDetail').val(company['detail']);
                        
                        buildings.forEach(function(item){
							var id = item['id'];
							this.row.add([
								id,
								item['name'] +'</a>',
								item['address'],
								item['floor_num'],
							])
							.node().id = 'trCompanyBuilding-' + item['id'];
							this.draw(false);
						},displayCompanyBuildingTable);
                        
                        stepper.next();
                    },
                    error:function(error){
                        console.log(error)
                    }
                });
            }
        });

        $('#previousBtn-2').on('click', function(){
            stepper.previous();
            $('#buildingForm').collapse('hide');
        });

        $('#previousBtn-3').on('click', function(){
            stepper.previous();
        });
    } );

    function getCreateCompanyInfo(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ route("companies.create") }}',
            type: "GET",
            success:function(response){
                $('#companyName').val("");
                $('#companyDetail').val("");
                $('#companyName').css('border', '');
                $('#companyDetail').css('border', '');
                $('#companyNameAlert').remove();
                $('#companyDetailAlert').remove();
                $('#companyOtherAlert').remove();
                buildingsId = [];
                stepper.to(1);
            },
            error:function(error){
                console.log(error)
            }
        });
    }
    

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
                        '<a href="#" id="'+ id + '" onClick="deleteBuilding(this.id)">@svg("trash", "feather-trash align-middle")</a>'
                    ]).node().id = 'trBuilding-' + id;
                    companyBuildingTable.draw(false);

                    $('#trBuilding-' + id +' td:eq(1)').attr('id', 'tdBuildingName-'+id);
                    $('#trBuilding-' + id +' td:eq(2)').attr('id', 'tdBuildingAddress-'+id);
                    $('#trBuilding-' + id +' td:eq(3)').attr('id', 'tdBuildingFloor-'+id); 
                    $('#trBuilding-' + id +' td:eq(4)').attr('id', 'tdBuildingAction-'+id); 
                    $('#trBuilding-' + id +' td:eq(4)').attr('class', 'table-action'); 
                    $('#trBuilding-' + id +' td:eq(4)').css('margin', '0px'); 

                    $('#buildingTable').collapse('show');
                    $('#buildingForm').collapse('hide');

                    buildingsId.push(id);
                }
				
			},
			error:function(error){
                console.log(error)
			}
		})
    }
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
				console.log(response);

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

    function deleteCompany(id){
        $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			url: '{{ route("companies.index") }}' + '/' + id,
			type: "DELETE",
			success:function(response){
				console.log(response);

                companyTable
                    .rows('#trCompany-'+ id)
                    .remove()
                    .draw();

				notyf.success(response['success']);
			},
			error:function(error){
				console.log(error)
			}
		})
    }
    @endcan
</script>

@endsection