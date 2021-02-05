@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">

    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3 justify-content-start">
        <a href="{{ route('readers.index') }}" style="padding-left: 12px">
            @svg('chevron-left', 'feather-chevron-left align-middle')  
        </a>
        <h3 style="padding-left: 12px">Create New<strong> Reader</strong></h3>
    </div>

    <!-- Form -->
    {!! Form::open(array('route' => 'readers.store','method'=>'POST')) !!}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body" id="createCardBody">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>
                                Serial Number
                                <span style="color: red; display:block; float:right"> *</span>
                            </label>
                            {!! Form::text('serial', null, array('placeholder' => 'R0000001','class' => "form-control", 'id' => 'createSerial')) !!}
                            @error('serial')
                                <script>$('#createSerial').css("border", "1px solid red");</script>
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label>
                                Mac Address
                                <span style="color: red; display:block; float:right"> *</span>
                            </label>
                            {!! Form::text('mac_addr', null, array('placeholder' => 'XX:XX:XX:XX','class' => "form-control", 'id' => 'createMacAdd')) !!}
                            @error('mac_addr')
                                <script>$('#createMacAdd').css("border", "1px solid red");</script>
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Button -->
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <a href="{{ route('readers.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    {!! Form::close() !!}
</div>
@endsection