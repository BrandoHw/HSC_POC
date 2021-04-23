<div class="iq-card">
    <div class="iq-card-body">
        <div class="row ">
            <div class="col-sm-2">
                <div class="nav flex-column nav-pills text-center mt-lg-5" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="border: 1px solid var(--iq-dark-border);border-radius:10px">
                    <a class="nav-link active" id="manage-personal" data-toggle="pill" href="#personal-tab" role="tab" aria-controls="v-pills-home" aria-selected="true" style="border-radius: 10px 10px 0 0">Personal Information</a>
                    <a class="nav-link" id="manage-password" data-toggle="pill" href="#password-tab" role="tab" aria-controls="v-pills-profile" aria-selected="false" style="border-radius: 0 0 10px 10px">Change Password</a>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="tab-content mt-0" id="v-pills-tabContent">
                    <div class="tab-pane fade active show" id="personal-tab" role="tabpanel" aria-labelledby="manage-personal">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                            <h4 class="card-title">Personal Information</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {!! Form::model($user, ['method' => 'POST', 'route' => ['users.profile']]) !!}
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
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="tab-pane fade {{ Session::get('password') ? 'active show':'' }}" id="password-tab" role="tabpanel" aria-labelledby="manage-password">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Change password</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            {!! Form::model($user, ['method' => 'POST', 'route' => ['users.password']]) !!}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="cname">Old Password: </label>
                                            {!! Form::password('old_password', array('class' => 'form-control', 'id'=>'old-password')) !!}
                                            @error('old_password')
                                                <script>
                                                    $('#old-password').css("border", "1px solid red");
                                                    $('#manage-personal').removeClass('active');
                                                    $('#personal-tab').removeClass('active show');
                                                    $('#manage-password').addClass('active');
                                                    $('#password-tab').addClass('active show');
                                                </script>
                                                <div class="alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="cname">New Password: </label>
                                            <a href="#" data-toggle="tooltip" data-placement="right" title="Password must be at least 8 characters." style="cursor: pointer; left-padding:0">
                                                <i class="ri-information-fill"></i>
                                            </a>
                                            {!! Form::password('new_password', array('class' => 'form-control', 'id'=>'new-password')) !!}
                                            @error('new_password')
                                                <script>
                                                    $('#new-password').css("border", "1px solid red");
                                                    $('#manage-personal').removeClass('active');
                                                    $('#personal-tab').removeClass('active show');
                                                    $('#manage-password').addClass('active');
                                                    $('#password-tab').addClass('active show');
                                                </script>
                                                <div class="alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="cname">Confirm New Password: </label>
                                            {!! Form::password('new_password_confirmation', array('class' => 'form-control', 'id'=>'new-password-confirmation')) !!}
                                            @error('new_password_confirmation')
                                                <script>
                                                    $('#new-password-confirmation').css("border", "1px solid red");
                                                    $('#manage-personal').removeClass('active');
                                                    $('#personal-tab').removeClass('active show');
                                                    $('#manage-password').addClass('active');
                                                    $('#password-tab').addClass('active show');
                                                </script>
                                                <div class="alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-5">
                                    <button type="submit" class="btn btn-primary">Update Password</button>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>