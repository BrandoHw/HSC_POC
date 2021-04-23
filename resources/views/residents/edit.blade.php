@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Resident: <strong>{{ $resident->full_name }}</strong></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::model($resident, ['method' => 'PATCH', 'route' => ['residents.update', $resident->resident_id]]) !!}
                        <div class="form-group">
                            <label for="viewFName">First Name:</label>
                            {!! Form::text('resident_fName', null, array('class' => "form-control", 'id' => 'viewFName', 'readonly')) !!}
                            @error('resident_fName')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="viewLName">Last Name:</label>
                            {!! Form::text('resident_lName', null, array('class' => "form-control", 'id' => 'viewLName', 'readonly')) !!}
                            @error('resident_lName')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="viewAge">Age:</label>
                            {!! Form::text('resident_age', null, array('class' => "form-control", 'id' => 'viewAge', 'readonly')) !!}
                            @error('resident_age')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="hasWheelchair">Wheelchair:</label>
                            {!! Form::text('wheelchair', ($resident->wheelchair) ? "Yes":"No", array('class' => "form-control", 'id' => 'hasWheelchair', 'readonly')) !!}
                            @error('wheelchair')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="hasWalkingCane">Walking Cane:</label>
                            {!! Form::text('walking_cane', ($resident->walking_cane) ? "Yes":"No", array('class' => "form-control", 'id' => 'hasWalkingCane', 'readonly')) !!}
                            @error('walking_cane')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="editTag">Beacon:</label>
                            {!! Form::select('beacon_id', $tags, null, ['placeholder' => 'Please select...', 'class' => 'form-control form-control', 'id' => 'editTag']) !!}
                            @error('beacon_id')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-center mt-5">
                            @can('resident-edit')
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                            @endcan
                            <a href="{{ route('residents.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    @error('beacon_id')<script>$('#editTag').attr('style', 'border: 1px solid red !important');</script>@enderror

    <script>
        $(function(){
            $('#editTag').select2({
                placeholder: "Please select ..."
            });
        })

    </script>
@endsection