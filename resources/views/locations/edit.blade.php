@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">

    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3 justify-content-start">
        <a href="{{ route('locations.index') }}" style="padding-left: 12px">
            @svg('chevron-left', 'feather-chevron-left align-middle')  
        </a>
        <h3 style="padding-left: 12px">Edit <strong>{{ $location->location_description }}</strong></h3>
    </div>

    <!-- Form -->
    {!! Form::model($location, ['method' => 'PATCH','route' => ['locations.update', $location->location_master_id]]) !!}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="iq-card">
                    <div class="iq-card-body">
                        <div class="form-row">
                            <div style="display:flex; flex-direction: row;">
                                <label class="col-form-label col-sm-4 text-sm-right">
                                    Name
                                </label>
                                 <input name="location_description" type="name" class="form-control col-sm-8" id="name-location-form" placeholder="Enter Location Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label col-sm-3 text-sm-right">
                                    Floor
                                </label>
                                <select id='selFloor-location-form' class="col-sm-8" name ="floor" ></select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label col-sm-3 text-sm-right">
                                    Type
                                </label>
                                <select id='selType-location-form' class="col-sm-8" name="location_type"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Button -->
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <a href="{{ route('locations.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    {!! Form::close() !!}
</div>
@endsection
@section("script")
<script>
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

    $("#selType-location-form").select2({
        data:selectType,
        minimumResultsForSearch: Infinity
    });

    $("#selFloor-location-form").select2({
        data:selectFloor,
        minimumResultsForSearch: Infinity
    });
    
</script>
@endsection

