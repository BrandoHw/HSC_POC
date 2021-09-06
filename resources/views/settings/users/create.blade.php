@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-9">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title"><strong>Add User:</strong></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::open(['route' => 'users.store','method'=>'POST']) !!}
                        <div class=" row align-items-center">
                            <div class="form-group col-sm-6">
                                <label for="f-name">First Name:</label>
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
                                <a href="#" data-toggle="tooltip" data-placement="right" title="Password will be autogenerated from username. Example: {username}@123" style="cursor: pointer; left-padding:0">
                                    <i class="ri-information-fill"></i>
                                </a>
                                {!! Form::text('username', null, ['placeholder' => 'Enter username', 'class' => "form-control", 'id' => 'username']) !!}
                                @error('username')
                                    <script>$('#username').addClass('is-invalid');</script>
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="gender">Gender:</label>
                                {!! Form::select('gender', ['M' => 'Male', 'F' => 'Female'], null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'gender']) !!}
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
                                {!! Form::select('role', $roles->pluck('name', 'id'), null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'role']) !!}
                            </div>
                            <div class="form-group col-sm-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" value="0" id="assign" name="assign" {{ $available ? '':'disabled' }}>
                                    <label class="custom-control-label" for="assign">Assign a beacon</label>
                                    @if(!$available)
                                        <div class="text-secondary"><i class="ri-information-fill text-warning"></i> <em>Cannot assign right now. No available beacon. </em></div>
                                    @endif
                                </div>
                                <div class="mt-2" id="tag-div" hidden>
                                    {!! Form::select('beacon_id', $tagsNull, null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'tag']) !!}
                                    @error('beacon_id')
                                        <script>
                                            $('#assign').prop("checked", true);
                                            $('#tag-div').prop("hidden", false);
                                        </script>
                                    @enderror
                                </div>
                            </div>
                            
                            @if(env('APP_TYPE') == 'hsc')
                                <div class="form-group col-sm-6">
                                    {{-- <a id="timeline-tooltip" href="#" data-toggle="tooltip" data-placement="right" title="Affects display staff color" style="cursor: pointer; left-padding:0">
                                        <i class="ri-information-fill"></i>
                                    </a> --}}
                                    <label for="type">User Type:</label>
                                    {!! Form::select('type_id', [2 => 'var(--staff-color)=Staff', 1 => 'var(--nurse-color)=Nurse'], null, ['class' => 'form-control', 'id' => 'type']) !!}
                                </div>
                            @endif
                        </div>
                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-primary">Save User</button>
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
        $('#type').select2({
            templateResult: formatState,
            templateSelection: formatState
        });

        function formatState (state){
            if (!state.id) {
                return state.text;
            }
            let code = state.text.split('=')[0];
            let name = state.text.split('=')[1];
            
            let $state = $(
                '<span><i class="ri-bookmark-fill" style="color:'+ code + '"></i>  ' + name +'</span>'
            )

            return $state
        }
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
</script>
@endsection