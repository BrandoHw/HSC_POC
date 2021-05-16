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
                            <label for="age">Age:</label>
                            {!! Form::number('resident_age', null, ['class' => "form-control", 'id' => 'age', 'min' => '1', 'max'=>'100', 'placeholder' => 'Enter age']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="gender">Gender:</label>
                            {!! Form::select('gender', ['M' => 'Male', 'F' => 'Female'], null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'gender']) !!}
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
            $('#age').inputSpinner();

            $('#gender').select2();
            $('#tag').select2();
            
            /* Display select2 error */
            let message = "Error Message";

            @error('resident_age')
            message = @json($message);
            $('#age').addClass('is-invalid');
            $('#age').siblings('.input-group').find('.input-group-prepend .btn').css('border-color', '#dc3545');
            $('#age').siblings('.input-group').find('.input-group-append .btn').css('border-color', '#dc3545');
            $('#age').siblings('.input-group').after('<div class="invalid-feedback">'+ message +'</div>');
            @enderror
            
            @error('gender')
            message = @json($message);
            $('#gender').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
            $('#gender').siblings('span').after('<div class="invalid-feedback" style="display:block">'+ message +'</div>');
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