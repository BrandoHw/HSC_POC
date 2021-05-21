@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Location: <strong>{{ $location->location_description }}</strong></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::model($location, ['method' => 'PATCH','route' => ['locations.update', $location->location_master_id]]) !!}
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input name="location_description" type="text" class="form-control" id="name-location-form" value="{{ $location->location_description }}" placeholder="Enter name">
                        </div>
                        <div hidden class="form-group">
                            <label for="floor">Floor:</label>
                            {!! Form::select('floor', $floors, $location->floor_id ?? null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'selFloor-location-form']) !!}
                        </div>
                        <div class="form-group">
                            <label for="location_type">Location Type:</label>
                            {!! Form::select('location_type', $types, $location->location_type_id ?? null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'selType-location-form']) !!}
                        </div>
                        <div class="text-center mt-5">
                            @can('location-edit')
                            <button type="submit" class="btn btn-primary">Update Location</button>
                            @endcan
                            <a href="{{ route('locations.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>   
</div>   
@endsection

@section("script")
<script>
    // var floors = []; //$floors
    // var types = []]; //$types

    // selectFloor = $.map(floors, function (obj) {
    //     obj.text = obj.text || obj.alias; // replace name with the property used for the text
    //     return obj;
    // });

    // selectType= $.map(types, function (obj) {
    //     obj.text = obj.text || obj.location_type; // replace name with the property used for the text
    //     obj.id = obj.type_id;
    //     return obj;
    // });

    // $("#selType-location-form").select2({
    //     data:selectType,
    //     minimumResultsForSearch: Infinity
    // });

    // $("#selFloor-location-form").select2({
    //     data:selectFloor,
    //     minimumResultsForSearch: Infinity
    // });
</script>

@endsection

