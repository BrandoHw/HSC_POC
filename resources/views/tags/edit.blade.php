@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Beacon ID: {{ $tag->id }}</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::model($tag, ['method' => 'PATCH', 'route' => ['beacons.update', $tag->id]]) !!}
                        <div class="form-group">
                            <label for="editMacAdd">Mac Address:</label>
                            {!! Form::text('beacon_mac', null, array('placeholder' => 'XX:XX:XX:XX','class' => "form-control", 'id' => 'editMacAdd')) !!}
                            @error('beacon_mac')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="editTagType">Type:</label>
                            {!! Form::select('beacon_type', $tagTypes, null, ['placeholder' => 'Please select...', 'class' => 'form-control form-control', 'id' => 'editTagType']) !!}
                            @error('beacon_type')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group" id="user-div" {{ ($tag->beacon_type == 1) ? '':'hidden' }}>
                            <label for="editUser">User:</label>
                            {!! Form::select('user_id', $usersNull, $tag->user->user_id ?? null, ['placeholder' => 'Please select...', 'class' => 'form-control form-control', 'id' => 'editUser']) !!}
                            @error('user_id')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group" id="resident-div" {{ ($tag->beacon_type == 2) ? '':'hidden' }}>
                            <label for="editResident">Resident:</label>
                            {!! Form::select('resident_id', $residentsNull, $tag->resident->resident_id ?? null, ['placeholder' => 'Please select...', 'class' => 'form-control form-control', 'id' => 'editResident']) !!}
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
    @error('beacon_mac')<script>$('#editMacAdd').css("border", "1px solid red");</script>@enderror
    @error('beacon_type')<script>$('#editTagType').attr('style', 'border: 1px solid red !important');</script>@enderror

    <script>
        $(function(){
            $('#editTagType').select2({
                placeholder: "Please select ..."
            });

            $('#editUser').select2({
                placeholder: "Please select ..."
            });
            
            $('#editResident').select2({
                placeholder: "Please select ..."
            });
        })

        $('#editTagType').on('change', function(){
            switch($(this).val()){
                case "1":
                    $('#user-div').prop('hidden', false);
                    $('#resident-div').prop('hidden', true);
                    $('#editUser').select2({
                        placeholder: "Please select ..."
                    });
                    break;
                case "2":
                    $('#user-div').prop('hidden', true);
                    $('#resident-div').prop('hidden', false);
                    $('#editResident').select2({
                        placeholder: "Please select ..."
                    });
                    break;
            }
        });
    </script>
@endsection