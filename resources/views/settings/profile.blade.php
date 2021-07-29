<div class="iq-card">
    <div class="iq-card-body">
        <div class="row ">
            <div class="col-sm-2">
                <div class="nav flex-column nav-pills text-center mt-lg-5" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="border: 1px solid var(--iq-dark-border);border-radius:10px">
                    <a class="nav-link active" id="manage-personal" data-toggle="pill" href="#personal-tab" role="tab" aria-controls="v-pills-home" aria-selected="true" style="border-radius: 10px 10px 0 0">Personal Information</a>
                    <a class="nav-link" id="manage-password" data-toggle="pill" href="#password-tab" role="tab" aria-controls="v-pills-profile" aria-selected="false" style="border-radius: 0 0 10px 10px">Change Password</a>
                    <a class="nav-link" id="generate-token" data-toggle="pill" href="#generate-tab" role="tab" aria-controls="v-pills-profile" aria-selected="false" style="border-radius: 0 0 10px 10px">Generate Token</a>
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
                            {!! Form::model($user, ['method' => 'POST', 'route' => ['users.profile'], 'enctype'=>"multipart/form-data"]) !!}
                                <input type="hidden" value="{{ $user->user_id }}" name="user_id" />
                                <div class=" row align-items-center">
                                    <div class="form-group col-sm-6">
                                        <label for="fname">First Name:</label>
                                        {!! Form::text('fName', null, ['placeholder' => 'Enter first name', 'class' => "form-control", 'id' => 'f-name']) !!}
                                        @error('fName')
                                            <script>$('#f-name').addClass('is-invalid');</script>
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="l-name">Last Name:</label>
                                        {!! Form::text('lName', null, ['placeholder' => 'Enter last name', 'class' => "form-control", 'id' => 'l-name']) !!}
                                        @error('lName')
                                            <script>$('#l-name').addClass('is-invalid');</script>
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="username">Username:</label>
                                        {!! Form::text('username', null, ['placeholder' => 'Enter username', 'class' => "form-control", 'id' => 'username']) !!}
                                        @error('username')
                                            <script>$('#username').addClass('is-invalid');</script>
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="gender">Gender:</label>
                                        {!! Form::select('gender', ['M' => 'Male', 'F' => 'Female'], $user->gender, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'gender']) !!}
                                        @error('gender')
                                            <script>$('#gender').addClass('is-invalid');</script>
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="email">Email:</label>
                                        {!! Form::text('email', null, ['placeholder' => 'Enter email', 'class' => "form-control", 'id' => 'email']) !!}
                                        @error('email')
                                            <script>$('#email').addClass('is-invalid');</script>
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="phone-num">Phone Number:</label>
                                        {!! Form::text('phone_number', null, ['placeholder' => 'Enter phone number', 'class' => "form-control", 'id' => 'phone-num']) !!}
                                        @error('phone_number')
                                            <script>$('#phone-num').addClass('is-invalid');</script>
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="role">Role:</label>
                                        {!! Form::select('role', $roles->pluck('name', 'id'), $user->roles[0]->id ?? null, ['class' => 'form-control', 'id' => 'role', 'disabled']) !!}
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" value="0" id="assign" name="assign" disabled {{ $current ? 'checked':'' }}>
                                            <label class="custom-control-label" for="assign">Assign a beacon</label>
                                            @if(!$available)
                                                <div class="text-secondary"><i class="ri-information-fill text-warning"></i> <em>Cannot assign right now. No available beacon. </em></div>
                                            @endif
                                        </div>
                                        <div class="mt-2" id="tag-div" {{ $current ? '':'hidden' }}>
                                            {!! Form::select('beacon_id', $tagsNull, null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'tag', 'disabled']) !!}
                                            @error('beacon_id')
                                                <script>
                                                    $('#assign').prop("checked", true);
                                                    $('#tag-div').prop("hidden", false);
                                                </script>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="image-input" >Profile Picture</label>
                                        <input id="image-input" type="file" class="form-control" name="image-input">
                                        <img src="#" alt = "" id="img-preview" width="200px" />   <!--for preview purpose -->
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
                                            {!! Form::password('old_password', ['class' => 'form-control', 'id'=>'old-password']) !!}
                                            @error('old_password')
                                                <script>
                                                    $('#manage-personal').removeClass('active');
                                                    $('#personal-tab').removeClass('active show');
                                                    $('#manage-password').addClass('active');
                                                    $('#password-tab').addClass('active show');
                                                </script>
                                                <script>$('#old-password').addClass('is-invalid');</script>
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="cname">New Password: </label>
                                            <a href="#" data-toggle="tooltip" data-placement="right" title="Password must be at least 8 characters." style="cursor: pointer; left-padding:0">
                                                <i class="ri-information-fill"></i>
                                            </a>
                                            {!! Form::password('new_password', ['class' => 'form-control', 'id'=>'new-password']) !!}
                                            @error('new_password')
                                                <script>
                                                    $('#manage-personal').removeClass('active');
                                                    $('#personal-tab').removeClass('active show');
                                                    $('#manage-password').addClass('active');
                                                    $('#password-tab').addClass('active show');
                                                </script>
                                                <script>$('#new-password').addClass('is-invalid');</script>
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="cname">Confirm New Password: </label>
                                            {!! Form::password('new_password_confirmation', ['class' => 'form-control', 'id'=>'new-password-confirmation']) !!}
                                            @error('new_password_confirmation')
                                                <script>
                                                    $('#manage-personal').removeClass('active');
                                                    $('#personal-tab').removeClass('active show');
                                                    $('#manage-password').addClass('active');
                                                    $('#password-tab').addClass('active show');
                                                </script>
                                                <script>$('#new-password-confirmation').addClass('is-invalid');</script>
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @error('new_password')
                                                @if(str_contains($message, 'match'))
                                                    <script>
                                                        $('#manage-personal').removeClass('active');
                                                        $('#personal-tab').removeClass('active show');
                                                        $('#manage-password').addClass('active');
                                                        $('#password-tab').addClass('active show');
                                                    </script>
                                                    <script>$('#new-password-confirmation').addClass('is-invalid');</script>
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @endif
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
                    <div class="tab-pane fade" id="generate-tab" role="tabpanel" aria-labelledby="generate-token">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                            <h4 class="card-title">Generate API Token</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            @include('settings.generate_api')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>