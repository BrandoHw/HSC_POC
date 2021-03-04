{!! Form::open(array('route' => 'beacons.store','method'=>'POST')) !!}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Create New Beacon:</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::open(array('route' => 'beacons.store','method'=>'POST')) !!}
                        <div class="form-group">
                            <label for="createMacAdd">Mac Address:</label>
                            {!! Form::text('beacon_mac', null, array('placeholder' => 'XX:XX:XX:XX','class' => "form-control", 'id' => 'createMacAdd')) !!}
                            @error('beacon_mac')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="createTagType">Type:</label>
                            {!! Form::select('beacon_type', $tagTypes, null, ['placeholder' => 'Please select...', 'class' => 'form-control form-control', 'id' => 'createTagType']) !!}
                            @error('beacon_type')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group" id="user-div" hidden>
                            <label for="createUser">User:</label>
                            {!! Form::select('user_id', $usersNull, null, ['placeholder' => 'Please select...', 'class' => 'form-control form-control', 'id' => 'createUser']) !!}
                            @error('user_id')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group" id="resident-div" hidden>
                            <label for="createResident">Resident:</label>
                            {!! Form::select('resident_id', $residentsNull, null, ['placeholder' => 'Please select...', 'class' => 'form-control form-control', 'id' => 'createResident']) !!}
                            @error('resident_id')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('beacons.index') }}" class="btn iq-bg-danger">Cancel</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    @error('beacon_mac')<script>$('#createMacAdd').css("border", "1px solid red");</script>@enderror
    @error('beacon_type')<script>$('#createTagType').attr('style', 'border: 1px solid red !important');</script>@enderror

    <script>
        $(function(){
            $('#createTagType').select2({
                placeholder: "Please select ..."
            });

            $('#createUser').select2({
                placeholder: "Please select ..."
            });
            
            $('#createResident').select2({
                placeholder: "Please select ..."
            });
        })

        $('#createTagType').on('change', function(){
            switch($(this).val()){
                case "1":
                    $('#user-div').prop('hidden', false);
                    $('#resident-div').prop('hidden', true);
                    $('#createUser').select2({
                        placeholder: "Please select ..."
                    });
                    break;
                case "2":
                    $('#user-div').prop('hidden', true);
                    $('#resident-div').prop('hidden', false);
                    $('#createResident').select2({
                        placeholder: "Please select ..."
                    });
                    break;
            }
        });
    </script>
@endsection