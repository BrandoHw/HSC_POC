@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-8">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title"><strong>Add Resident:</strong></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::open(['route' => 'residents.store','method'=>'POST']) !!}
                    <div class=" row align-items-center">
                        <div class="form-group col-sm-6">
                            <label for="f-name">First Name:</label>
                            {!! Form::text('resident_fName', null, ['class' => "form-control", 'id' => 'f-name', 'placeholder' => 'Enter first name']) !!}
                            @error('resident_fName')
                                <script>$('#f-name').addClass('is-invalid');</script>
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="l-name">Last Name:</label>
                            {!! Form::text('resident_lName', null, ['class' => "form-control", 'id' => 'l-name', 'placeholder' => 'Enter last name']) !!}
                            @error('resident_lName')
                                <script>$('#l-name').addClass('is-invalid');</script>
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="dob">Date of Birth:</label>
                            <div class="input-group date">
                                {!! Form::text('resident_dob', null, ['class' => "form-control", 'id' => 'dob', 'style' => 'background-color: white', 'placeholder' => 'Please select...']) !!}
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="gender">Gender:</label>
                            {!! Form::select('resident_gender', ['M' => 'Male', 'F' => 'Female'], null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'gender']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="room">Room:</label>
                            {!! Form::select('location_room_id', $rooms, null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'room']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            <label>External Support:</label>
                            <div class="row align-items-center ml-2" id="support-row">
                                <div class="custom-control custom-checkbox mr-3">
                                    {!! Form::checkbox('wheelchair', 0, null, ['class' => 'custom-control-input', 'id' => 'wheelchair']) !!}
                                    <label class="custom-control-label" for="wheelchair">Wheelchair</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    {!! Form::checkbox('walking_cane', 0, null, ['class' => 'custom-control-input', 'id' => 'walking-cane']) !!}
                                    <label class="custom-control-label" for="walking-cane">Walking Cane</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-6 mt-2">
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
                    </div>
                    <hr>
                    <p class="iq-bg-primary pl-3 pr-3 pt-2 pb-2 rounded">Emergency Contact Person</p>
                    <div class=" row align-items-center">
                        <div class="form-group col-sm-6">
                            <label for="name">Name:</label>
                            {!! Form::text('contact_name', null, ['class' => "form-control", 'id' => 'name', 'placeholder' => 'Enter name']) !!}
                            @error('contact_name')
                                <script>$('#name').addClass('is-invalid');</script>
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="realtionship">Relationship:</label>
                            {!! Form::select('contact_relationship', $relationship, null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'relationship']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="phone-num-1">Phone Number 1:</label>
                            {!! Form::text('contact_phone_num_1', null, ['class' => "form-control", 'id' => 'phone-num-1', 'placeholder' => 'Enter phone number']) !!}
                            @error('contact_phone_num_1')
                                <script>$('#phone-num-1').addClass('is-invalid');</script>
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="phone-num-2">Phone Number 2:  <em class="text-secondary"><small>[Optional]</small></em></label>
                            {!! Form::text('contact_phone_num_2', null, ['class' => "form-control", 'id' => 'phone-num-2', 'placeholder' => 'Enter phone number']) !!}
                            @error('contact_phone_num_2')
                                <script>$('#phone-num-2').addClass('is-invalid');</script>
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="address">Address:</label>
                            {!! Form::textarea('contact_address', null, ['class' => "form-control", 'size' => '30x5', 'id' => 'address', 'placeholder' => 'Enter address']) !!}
                            @error('contact_address')
                                <script>$('#address').addClass('is-invalid');</script>
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-primary">Save Resident</button>
                        <a href="{{ route('residents.index') }}" class="btn btn-secondary">Cancel</a>
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
            $('#dob').flatpickr({
                maxDate: 'today'
            });
            
            $('#gender').select2();
            $('#room').select2();
            $('#tag').select2();
            $('#relationship').select2();
            
            /* Display select2 error */
            let message = "Error Message";

            @error('resident_dob')
            message = @json($message);
            $('#dob').addClass('is-invalid');
            $('#dob').siblings('.input-group-append').css({
                'border': '1px solid #dc3545',
                'border-radius': '0 0.25rem 0.25rem 0',
            });
            $('#dob').siblings('.input-group-append').after('<div class="invalid-feedback">'+ message +'</div>');
            @enderror
            
            @error('resident_gender')
            message = @json($message);
            $('#gender').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
            $('#gender').siblings('span').after('<div class="invalid-feedback" style="display:block">'+ message +'</div>');
            @enderror

            @error('location_room_id')
            message = @json($message);
            $('#room').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
            $('#room').siblings('span').after('<div class="invalid-feedback" style="display:block">'+ message +'</div>');
            @enderror

            @error('contact_relationship')
            message = @json($message);
            $('#relationship').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
            $('#relationship').siblings('span').after('<div class="invalid-feedback" style="display:block">'+ message +'</div>');
            @enderror

            @error('beacon_id')
            message = @json($message);
            $('#tag').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
            $('#tag').siblings('span').after('<div class="invalid-feedback" id="invalid-tag" style="display:block">'+ message +'</div>');
            $('#tag').val('').trigger('change');
            @enderror
        })

        $('#wheelchair').on('change', function(){
            if($('#wheelchair').is(':checked')){
                $('#wheelchair').val('1');
            } else {
                $('#wheelchair').val('0');
            }
        })

        $('#walking-cane').on('change', function(){
            if($('#walking-cane').is(':checked')){
                $('#walking-cane').val('1');
            } else {
                $('#walking-cane').val('0');
            }
        })

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