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
                    <div class="iq-card-header-toolbar d-flex align-items-center">
                        <button type="button" class="btn btn-warning" id="resetPassword">Reset Password</button>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->user_id]]) !!}
                        <input type="hidden" value="{{ $user->user_id }}" name="user_id" id="user-id" />
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
                                <label for="lname">Last Name:</label>
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
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="email">Email:</label>
                                {!! Form::text('email', null, ['placeholder' => 'Enter email', 'class' => "form-control", 'id' => 'email']) !!}
                                @error('email')
                                    <script>$('#email').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="phone-num">Phone Number:</label>
                                {!! Form::text('phone_number', null, ['placeholder' => 'Enter phone number', 'class' => "form-control", 'id' => 'phone-num']) !!}
                                @error('phone_number')
                                    <script>$('#phone-num').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="role">Role:</label>
                                {!! Form::select('role', $roles->pluck('name', 'id'), $user->roles[0]->id, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'role']) !!}
                                @error('role')
                                    <script>$('#role').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" value="0" id="assign" name="assign" {{ $available ? '':'disabled' }} {{ $current ? 'checked':'' }}>
                                    <label class="custom-control-label" for="assign">Assign a beacon</label>
                                    @if(!$available)
                                        <div class="text-secondary"><i class="ri-information-fill text-warning"></i> <em>Cannot assign right now. No available beacon. </em></div>
                                    @endif
                                </div>
                                <div class="mt-2" id="tag-div" {{ $current ? '':'hidden' }}>
                                    {!! Form::select('beacon_id', $tagsNull, null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'tag']) !!}
                                    @error('beacon_id')
                                        <script>
                                            $('#assign').prop("checked", true);
                                            $('#tag-div').prop("hidden", false);
                                        </script>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="type">User Type:</label>
                                {!! Form::select('type_id', [2 => 'Staff', 1 => 'Nurse'], null, ['class' => 'form-control', 'id' => 'type']) !!}
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
    <!-- Reset Password: Confirmation -->
    <div class="modal fade" id="confirmation-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                    <div class="container-fluid bd-example-row">
                        <div class="row justify-content-center iq-bg-danger">
                            <i class="ri-error-warning-fill text-danger" style="font-size: 85px; margin: -15px"></i>
                        </div>
                        <div class="row mt-3 justify-content-center mt-2">
                            <div class="h4 font-weight-bold">Reset password?</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">The user password will be reset to {username}@123.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" id="cancel-btn" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger m-1" id="reset-btn" onClick="confirmResetPassword(this.id)">Yes, reset it</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(function(){
        /* Initialise select2 */
        $('#gender').select2();
        $('#role').select2();
        $('#tag').select2();

        @if($available)
            @if(!empty($user->tag))
                $('#tag').select2('val', [@json($user->tag->beacon_id)]);
            @else
                $('#tag').val('').trigger('change');
            @endif
        @else
            $('#assign').prop('disabled', true);
            $('#tag').prop('disabled', true);
        @endif

        @cannot('user-edit')
            $('#resetPassword').prop('disabled', true);
            $('#f-name').prop('disabled', true);
            $('#l-name').prop('disabled', true);
            $('#username').prop('disabled', true);
            $('#email').prop('disabled', true);
            $('#gender').prop('disabled', true);
            $('#email').prop('disabled', true);
            $('#phone-num').prop('disabled', true);
            $('#role').prop('disabled', true);
            $('#assign').prop('disabled', true);
            $('#tag').prop('disabled', true);
        @endcannot

        /* Display select2 error */
        let message = "Error Message";

        @error('gender')
        message = @json($message);
        $('#gender').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
        $('#gender').siblings('span').after('<div class="invalid-feedback" style="display:block">'+ message +'</div>');
        @enderror

        @error('role')
        message = @json($message);
        $('#role').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
        $('#role').siblings('span').after('<div class="invalid-feedback" style="display:block">'+ message +'</div>');
        @enderror

        @error('beacon_id')
        message = @json($message);
        $('#tag').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
        $('#tag').siblings('span').after('<div class="invalid-feedback" id="invalid-tag" style="display:block">'+ message +'</div>');
        $('#tag').val('').trigger('change');
        @enderror
    });

    $('#assign').on('change', function(){
        if($('#assign').is(':checked')){
            if($('#tag').hasClass("select2-hidden-accessible")){
                $('#tag').select2('destroy');
            }
            $('#tag-div').prop('hidden', false);
            if(!$('#tag').hasClass("select2-hidden-accessible")){
                $('#tag').select2();
            }
            if($('#invalid-tag').length){
                $('#invalid-tag').remove();
            }
            $('#assign').val('1');
        } else {
            $('#assign').val('0');
            $('#tag-div').prop('hidden', true);
        }
        $('#tag').val('').trigger('change');
    })

    @can('user-edit')
    $('#resetPassword').on('click', function(){
        $('#cancel-btn').prop('hidden', false);
        $('#reset-btn').html('Yes, reset it');
        $('#reset-btn').prop('disabled', false);
        $('#reset-btn').css('background-color', 'var(--iq-danger)');
        $('#reset-btn').css('border-color', 'var(--iq-danger)');
        $('#confirmation-modal').modal('toggle');
    })
    
    function confirmResetPassword(id){
        console.log('inside');
        let cancel_btn = $('#cancel-btn');
        let reset_btn = $('#reset-btn');
        let modal = $('#confirmation-modal');
        
        cancel_btn.prop('hidden', true);
        reset_btn.prop('disabled', true);
        reset_btn.html('<i class="fa fa-circle-o-notch fa-spin"></i>Reseting');

        let result = {
            user_id: $('#user-id').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        $.ajax({
            url: '{{ route("users.reset") }}',
            type: "POST",
            data: result,
            success:function(response){
                let errors = response['errors'];
                if($.isEmptyObject(response['success'])){
                    console.log(errors);
                } else {
                    reset_btn.css('background-color', 'var(--iq-success)');
                    reset_btn.css('border-color', 'var(--iq-success)');
                    reset_btn.html('<i class="fa fa-check"></i>Reset');
                    setTimeout(function() {
                        modal.modal('toggle');
                    }, 500);
				    notyf.success(response['success']);
                }
            },
            error:function(error){
                console.log(error);
            }
        });
    }
    @endcan
</script>
@endsection