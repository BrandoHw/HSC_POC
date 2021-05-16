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
                    {!! Form::open(array('route' => 'residents.store','method'=>'POST')) !!}
                    <div class=" row align-items-center">
                        <div class="form-group col-sm-6">
                            <label for="createFName">First Name:</label>
                            {!! Form::text('resident_fName', null, array('class' => "form-control", 'id' => 'f-name', 'placeholder' => 'Enter first name')) !!}
                            @error('resident_fName')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="createLName">Last Name:</label>
                            {!! Form::text('resident_lName', null, array('class' => "form-control", 'id' => 'l-name', 'placeholder' => 'Enter last name')) !!}
                            @error('resident_lName')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="createAge">Age:</label>
                            {!! Form::number('resident_age', null, array('class' => "form-control", 'id' => 'age', 'min' => '1', 'max'=>'100', 'placeholder' => 'Enter age')) !!}
                            @error('resident_age')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="editTag">Beacon:</label>
                            {!! Form::select('tag', $tags, null, ['placeholder' => 'Please select...', 'class' => 'form-control form-control-lg', 'id' => 'tag']) !!}
                            @error('tag')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label>Support:</label>
                            <div class="row align-items-center ml-2" id="support-row">
                                <div class="custom-control custom-checkbox mr-3">
                                    {!! Form::checkbox('wheelchair', 1, null, array('class' => 'custom-control-input', 'id' => 'wheelchair')) !!}
                                    <label class="custom-control-label" for="wheelchair">Wheelchair</label>
                                </div>
                                @error('wheelchair')
                                    <div class="alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="custom-control custom-checkbox mr-3">
                                    {!! Form::checkbox('walking_cane', 1, null, array('class' => 'custom-control-input', 'id' => 'walking-cane')) !!}
                                    <label class="custom-control-label" for="walking-cane">Walking Cane</label>
                                </div>
                                @error('walking_cane')
                                    <div class="alert-danger">{{ $message }}</div>
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
            $('#tag').select2({
                placeholder: "Please select ..."
            });

            $('#age').inputSpinner();
        })

        $('#assign').on('change', function(){
            if($('#assign').is(':checked')){
                if($('#target').hasClass("select2-hidden-accessible")){
                    $('#target').select2('destroy');
                }
                $('#target-div').prop('hidden', false);
                if(!$('#target').hasClass("select2-hidden-accessible")){
                    $('#target').select2({
                        multiple: false,
                        closeOnSelect: false,
                        scrollAfterSelect: false,
                        allowClear: false,
                        selectionCssClass: 'form-control',
                        placeholder: "Please select target..."
                    });
                }
                $('#assign').val('1');
            } else {
                $('#assign').val('0');
                $('#target-div').prop('hidden', true);
            }
            $('#target').val('').trigger('change');
        })

    </script>
@endsection