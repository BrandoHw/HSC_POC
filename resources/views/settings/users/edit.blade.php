@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-9">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Member ID: {{ $user->user_id }}</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->user_id]]) !!}
                        <div class=" row align-items-center">
                            <div class="form-group col-sm-6">
                            <label for="fname">First Name:</label>
                            {!! Form::text('fName', null, array('class' => "form-control", 'id' => 'editFName')) !!}
                            @error('fName')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="lname">Last Name:</label>
                                {!! Form::text('lName', null, array('class' => "form-control", 'id' => 'editLName')) !!}
                                @error('lName')
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="uname">Phone Number:</label>
                                {!! Form::text('phone_number', null, array('class' => "form-control", 'id' => 'editPhoneNum')) !!}
                                @error('phone_number')
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="editTag">Role:</label>
                                {!! Form::select('user_type', $userTypes, null, ['placeholder' => 'Please select...', 'class' => 'form-control form-control', 'id' => 'editUserType']) !!}
                                @error('user_type')
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class=" row align-items-center">
                            <div class="form-group col-sm-6">
                                <label for="uname">Username:</label>
                                {!! Form::text('username', null, array('class' => "form-control", 'id' => 'editUsername')) !!}
                                @error('username')
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="cname">Password: </label>
                                <a href="#" data-toggle="tooltip" data-placement="right" title="Leave blank if do not wish to change password..." style="cursor: pointer; left-padding:0">
                                <i class="ri-information-fill"></i>
                                </a>
                                    {!! Form::password('password', array('class' => 'form-control', 'id'=>'editPassword')) !!}
                                @error('password')
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="editTag">Card:</label>
                                {!! Form::select('beacon_id', $tagsNull, null, ['placeholder' => 'Please select...', 'class' => 'form-control form-control', 'id' => 'editTag']) !!}
                                @error('beacon_id')
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="cname">Permission: </label>
                                <a href="#" data-toggle="tooltip" data-placement="right" title="Tick to allow this member to make changes to the project" style="cursor: pointer; left-padding:0">
                                <i class="ri-information-fill"></i>
                                </a>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="user_right">
                                    <label class="custom-control-label" for="user_right">Administrator</label>
                                 </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary" disabled>Save</button>
                            <a href="{{ route('settings.index') }}" class="btn iq-bg-danger">Cancel</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection