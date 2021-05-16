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
                        <input type="hidden" value="{{ $user->user_id }}" name="user_id" />
                        <div class=" row align-items-center">
                            <div class="form-group col-sm-6">
                                <label for="fname">First Name:</label>
                                {!! Form::text('fName', null, ['placeholder' => 'Enter first name', 'class' => "form-control", 'id' => 'f-name', 'disabled']) !!}
                                @error('fName')
                                    <script>$('#f-name').addClass('is-invalid');</script>
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="lname">Last Name:</label>
                                {!! Form::text('lName', null, ['placeholder' => 'Enter last name', 'class' => "form-control", 'id' => 'l-name', 'disabled']) !!}
                                @error('lName')
                                    <script>$('#l-name').addClass('is-invalid');</script>
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="username">Username:</label>
                                {!! Form::text('username', null, ['placeholder' => 'Enter username', 'class' => "form-control", 'id' => 'username', 'disabled']) !!}
                                @error('username')
                                    <script>$('#username').addClass('is-invalid');</script>
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="gender">Gender:</label>
                                {!! Form::select('gender', ['M' => 'Male', 'F' => 'Female'], $user->gender, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'gender', 'disabled']) !!}
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="email">Email:</label>
                                {!! Form::text('email', null, ['placeholder' => 'Enter email', 'class' => "form-control", 'id' => 'email', 'disabled']) !!}
                                @error('email')
                                    <script>$('#email').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="phone-num">Phone Number:</label>
                                {!! Form::text('phone_number', null, ['placeholder' => 'Enter phone number', 'class' => "form-control", 'id' => 'phone-num', 'disabled']) !!}
                                @error('phone_number')
                                    <script>$('#phone-num').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="role">Role:</label>
                                {!! Form::select('role', $roles->pluck('name', 'id'), $user->roles[0]->id, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'role', 'disabled']) !!}
                                @error('role')
                                    <script>$('#role').css("border", "1px solid red");</script>
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" value="0" id="assign" name="assign" disabled {{ $available ? '':'disabled' }} {{ $current ? 'checked':'' }}>
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
                                            $('#tag').val('').trigger('change');
                                        </script>
                                    @enderror
                                </div>
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

        @can('user-edit')
            $('#f-name').prop('disabled', false);
            $('#l-name').prop('disabled', false);
            $('#username').prop('disabled', false);
            $('#email').prop('disabled', false);
            $('#gender').prop('disabled', false);
            $('#email').prop('disabled', false);
            $('#phone-num').prop('disabled', false);
            $('#role').prop('disabled', false);
            $('#assign').prop('disabled', false);
            $('#tag').prop('disabled', false);
        @endcan

        /* Display select2 error */
        let message = "Error Message";

        @error('gender')
        /* Profile Tag Error */
        message = @json($message);
        $('#gender').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
        $('#gender').siblings('span').after('<div class="invalid-feedback" style="display:block">'+ message +'</div>');
        @enderror

        @error('role')
        /* Profile Tag Error */
        message = @json($message);
        $('#role').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
        $('#role').siblings('span').after('<div class="invalid-feedback" style="display:block">'+ message +'</div>');
        @enderror

        @error('beacon_id')
        /* Profile Tag Error */
        message = @json($message);
        $('#tag').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
        $('#tag').siblings('span').after('<div class="invalid-feedback" style="display:block">'+ message +'</div>');
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
            $('#assign').val('1');
        } else {
            $('#assign').val('0');
            $('#tag-div').prop('hidden', true);
        }
        $('#tag').val('').trigger('change');
    })
</script>
@endsection