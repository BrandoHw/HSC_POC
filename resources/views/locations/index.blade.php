@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row mb-3 mb-xl-4">
        <div class="col-auto d-none d-sm-block">
            @can('floor-list')
            <a href="{{ route('floors.index') }}"class="btn btn-primary">Floor Management</a>
            @endcan
            @can('location-list')
            <a href="{{ url('map/1/edit') }}"class="btn btn-primary">Edit Map</a>
            @endcan
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-body">
                    <div class="iq-search-bar row justify-content-between">
                        <form action="#" class="searchbox">
                            <input type="text" id="myCustomSearchBox" class="text search-input" placeholder="Type here to search...">
                            <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                        </form>
                        <div class="col-4 row justify-content-end">
                            @can('location-create')
                            <a class="btn btn-primary" href="#" id ="createLocationButton" style="margin-right: 10px"><i class="ri-add-line"></i>Add Location</a>
                            @endcan
                            @can('location-delete')
                            <a class="btn btn-danger" href="#" id ="deleteLocationButton"> Delete</a>
                            @endcan
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="locationTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col">Location Name</th>
                                    <th scope="col">Location Type</th>
                                    <th scope="col">Floor</th>
                                </tr>
                        </thead>
                            <tbody>
                                @foreach ($locations as $location)
                                    <tr href="{{ route('locations.edit',$location->location_master_id) }}">
                                        <td>{{ $location->location_master_id }}</td>
                                        <td>{{ $location->location_description }}</td>
                                        <td>{{ $location->type->location_type}}</td>
                                        <td>{{ $location->floor_level->alias}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@can('location-create')
<!-- Create Location Modal -->
<div class="modal fade" id="createLocationModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        @include('locations.create')
    </div>
</div>
@endcan

@can('location-delete')
<!-- Delete Button Alerts -->
<div class="alert text-white bg-primary" role="alert" id="alert">
    <div class="iq-alert-text">A simple <b>primary</b> alert—check it out!</div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <i class="ri-close-line"></i>
    </button>
</div>
@endcan


@endsection 

@section("script")
<script>
    /* Initiate dataTable */
    var dTable = $('#locationTable').DataTable({
            order: [[3, 'asc']],
            columns:[
                {data: 'location_master_id' },
                {data: 'location_description'},
                {data: 'type.location_type'},
                {data: 'floor_level.alias'},
            ],
    })

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#locationTable tbody tr td:not(:first-child)').click(function () {
        window.location.href = $(this).parent('tr').attr('href');
    });

    @can('location-create')
    $( "#createLocationButton" ).click(function() {
        $('#createLocationModal').modal('show');
    });
    @endcan

    @can('location-delete')
    $( "#deleteLocationButton" ).click(function() {
        deleteLocation();
    });
    @endcan
  
    var floors = <?php echo $floors; ?>;
    var types = <?php echo $types; ?>;

    selectFloor = $.map(floors, function (obj) {
        obj.text = obj.text || obj.alias; // replace name with the property used for the text
        return obj;
    });
    selectType= $.map(types, function (obj) {
        obj.text = obj.text || obj.location_type; // replace name with the property used for the text
        obj.id = obj.type_id;
        return obj;
    });
    
    $("#selFloor-location-form").select2({
        data:selectFloor,
        minimumResultsForSearch: Infinity
    });
    
    $("#selType-location-form").select2({
        data:selectType,
        minimumResultsForSearch: Infinity
    });

    @can('location-create')
    function createLocation(){
        console.log("createLocation")
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var data = {
            location_description: $('#name-location-form').val(),
            location_type_id: $('#selType-location-form').select2('data')[0].type_id,
            floor: $('#selFloor-location-form').select2('data')[0].id,
        };
        $.ajax({
            url: '{{ route("locations.store")}}',
            type: "POST",
            data: data,
            success:function(response){
                console.log(response);
                console.log(response['location']);
                if($.isEmptyObject(response['success'])){
                    var errors = response['errors'];
                }else if(!$.isEmptyObject(response['failure'])) {
                    notyf.failure(response['failure']);
                }else{
                    dTable.row.add(response['location']).draw(true);
                    notyf.success(response['success']);
                }

                $('#name-location-form').val('');
                $('#createLocationModal').modal('hide');
            },
            error:function(error){
                console.log(error)
            }
        })
    }
    @endcan

    @can('location-delete')
    function deleteLocation(){
        if (dTable.rows('.selected').data().length <= 0 ){
            alert("No items selected");
        }else{
            var ids = new Array();
            for (i = 0; i<dTable.rows('.selected').data().length; i++){
                ids[i] = dTable.rows('.selected').data()[i]['location_master_id'];
            }
            console.log(ids);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = {
                ids: ids
            };
            $.ajax({
                url: '{{ route("locations.delete")}}',
                type: "POST",
                data: data,
                success:function(response){
                    console.log(response);
                    if($.isEmptyObject(response['success'])){
                        var errors = response['errors'];
                    }else if(!$.isEmptyObject(response['failure'])) {
                        notyf.failure(response['failure']);
                    }else{
                        dTable.clear()
                        for (const property in response['locations']) {
                            dTable.row.add(response['locations'][property]).draw(true);
                        }
                        notyf.success(response['success']);
                    }
                },
                error:function(error){
                    console.log(error)
                }
            })
        }
    }
    @endcan

    $( function() {
        dialog = $( "#alert" ).dialog({
            autoOpen: false,
            closeOnEscape: false,
            open: function(event, ui) {
                $(".ui-dialog-titlebar-close", ui.dialog || ui).hide();
            },
            modal: true,
        });
    
  } );
</script>
@endsection