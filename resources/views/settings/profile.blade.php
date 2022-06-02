<style>
i:active {
    background-color: #a8f2ef;
}

.fa-clipboard{
    font-size:20px;
    cursor: pointer;
}
.tfa-info > div {
    /* flex: 1 1 auto; */
    text-align: center;
    margin: 5px;  
}
.button-tooltip-container {
    display: flex;
    align-items: center;
    margin-top: 16px;
    min-height: 30px;

}
#custom-tooltip {
    display: none;
    margin-left: 40px;
    padding: 5px 12px;
    background-color: #000000df;
    border-radius: 4px;
    color: #fff;
}
</style>
<div class="iq-card">
    <div class="iq-card-body">
        <div class="row ">
            <div class="col-sm-2">
                <div class="nav flex-column nav-pills text-center mt-lg-5" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="border: 1px solid var(--iq-dark-border);border-radius:10px">
                    <a class="nav-link active" id="manage-personal" data-toggle="pill" href="#personal-tab" role="tab" aria-controls="v-pills-home" aria-selected="true" style="border-radius: 10px 10px 0 0">Personal Information</a>
                    <a class="nav-link" id="manage-password" data-toggle="pill" href="#password-tab" role="tab" aria-controls="v-pills-profile" aria-selected="false" style="border-radius: 0 0 10px 10px">Change Password</a>
                    <a class="nav-link" id="manage-2fa" data-toggle="pill" href="#fa-tab" role="tab" aria-controls="v-pills-profile" aria-selected="false" style="border-radius: 0 0 10px 10px">Toggle 2FA</a>
                    @if(env('APP_TYPE') == 'hsc')
                        @can('token-list')
                            <a class="nav-link" id="generate-token" data-toggle="pill" href="#generate-tab" role="tab" aria-controls="v-pills-profile" aria-selected="false" style="border-radius: 0 0 10px 10px">Generate Token</a>
                        @endcan
                    @endif
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
                    <div class="tab-pane fade" id="fa-tab" role="tabpanel" aria-labelledby="manage-2fa">
                        <div class="iq-card-header d-flex">
                            <div class="iq-header-title" style="margin-right:20px">
                                <h4 class="card-title">Toggle two-factor authentication</h4>
                            </div>

                            <a href="#" data-toggle="tooltip" data-placement="right" title="When enabled a QR Code will be generated that must be scanned with an
                            authenticator app such as Authy or Google Authenticator. If disabled and enabled the new QR code must be scanned again." 
                            style="cursor: pointer; left-padding:0">
                                <i class="ri-information-fill"></i>
                            </a>
                        </div>
                        <div class="iq-card-body">
                            <div class="d-flex justify-content-center tfa-info" style="gap:50px">
                                @if (auth()->user()->two_factor_secret)
                                <div>
                                    {!!auth()->user()->twoFactorQrCodeSvg()!!}
                                </div>    
                                <div class="d-flex justify-content-center" style="display: none!important" id="recovery-container">
                                    <ul id="recovery-codes">
                                    </ul>
                                    <div class="button-tooltip-container">
                                        <i id="copy-button" class="fa fa-clipboard" aria-hidden="true"></i>
                                        <span id="custom-tooltip" style="position: absolute">Copied!</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                         
                            <div class="justify-content-center align-items-center" style="display:flex; gap: 20px">
                                <form method="POST" action="/user/two-factor-authentication">
                                    @csrf
                                    @if (auth()->user()->two_factor_secret)
                                            @method('DELETE')
                                            <button class="btn btn-danger">Disable</button>
                                        @else
                                            <button class="btn btn-primary">Enable</button>
                                    @endif
                                </form> 
                                <button id="recovery-button" class="btn btn-primary">Recovery Codes</button>
                                <img src={{url('/css/images/ajax-loader.gif')}} id="loading-indicator" style="display:none" />
                            </div>
                        </div>
                    </div>
                    @if(env('APP_TYPE') == 'hsc')
                        @can('token-list')
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
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $ (function(){
        var copyText = '';
        getRecoveryCodes =  function (){
            $.ajax({
                url: '/user/two-factor-recovery-codes/',
                type: "GET",
                success:function(response){
                    console.log("onload");
                    console.log(response);
                    var list = $('#recovery-codes')
                    $.each(response, function(i){
                        var li = $('<li/>')
                        .text(response[i])
                        .appendTo(list);

                        copyText = copyText + response[i] + "\n";
                    })

                    console.log(copyText);
                    /* Copy the text inside the text field */
                    $("#recovery-button" ).show();
                    $("#loading-indicator" ).hide();
               
                },
                error:function(error){
                    console.log(error);
                    var li = $('<li/>')
                    .text("There was an error retrieving the recovery codes")
                    .appendTo(list);
                    $("#recovery-button" ).show();
                    $("#loading-indicator" ).hide();
                }
            });
        }

        $("#copy-button" ).click(function() {
            navigator.clipboard.writeText(copyText);
            $("#custom-tooltip").css('display', 'inline');
            setTimeout( function() {
                $("#custom-tooltip").hide();
            }, 1000);
        });
        
        $("#recovery-button" ).click(function() {
            getRecoveryCodes();
            $("#recovery-container").show();
            $("#recovery-button" ).hide();
            $("#loading-indicator" ).show();
        });
      
    });
</script>