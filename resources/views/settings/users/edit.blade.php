@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-9">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">User: <strong>{{ $user->full_name }}</strong></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->user_id]]) !!}
                        <div class=" row align-items-center">
                            <div class="form-group col-sm-6">
                                <label for="fname">First Name:</label>
                                {!! Form::text('fName', null, array('class' => "form-control", 'id' => 'fName')) !!}
                                @error('fName')
                                    <script>$('#fName').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="lname">Last Name:</label>
                                {!! Form::text('lName', null, array('class' => "form-control", 'id' => 'lName')) !!}
                                @error('lName')
                                    <script>$('#lName').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="username">Username:</label>
                                {!! Form::text('username', null, array('class' => "form-control", 'id' => 'username')) !!}
                                @error('username')
                                    <script>$('#username').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="email">Gender:</label>
                                {!! Form::select('gender', ['M' => 'Male', 'F' => 'Female'], $user->gender, ['class' => 'form-control', 'id' => 'gender']) !!}
                                @error('gender')
                                    <script>$('#gender').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="email">Email:</label>
                                {!! Form::text('email', null, array('class' => "form-control", 'id' => 'email')) !!}
                                @error('email')
                                    <script>$('#email').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="phone_number">Phone Number:</label>
                                {!! Form::text('phone_number', null, array('class' => "form-control", 'id' => 'phoneNum')) !!}
                                @error('phone_number')
                                    <script>$('#phoneNum').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="editTag">Role:</label>
                                {!! Form::select('role', $roles->pluck('name', 'id'), $user->roles[0]->id, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'role']) !!}
                                @error('role')
                                    <script>$('#role').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="editTag">Beacon:</label>
                                {!! Form::select('beacon_id', $tagsNull, null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'tag']) !!}
                                @error('beacon_id')
                                    <script>$('#tag').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            @can('user-edit')
                            <button type="submit" class="btn btn-primary">Update User</button>
                            @endcan
                            @php(session(['user' => 'page']))
                            <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
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