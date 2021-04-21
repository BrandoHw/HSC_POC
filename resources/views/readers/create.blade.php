@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title"><strong>Add Gateway:</strong></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::open(array('route' => 'gateways.store','method'=>'POST')) !!}
                        <div class="form-group">
                            <label for="serial">Serial Number:</label>
                            {!! Form::text('serial', null, array('placeholder' => 'Exp: L1-CA-01', 'class' => "form-control", 'id' => 'createSerial')) !!}
                            @error('serial')
                                <script>$('#createSerial').css("border", "1px solid red");</script>
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="serial">Mac Address:</label>
                            {!! Form::text('mac_addr', null, array('placeholder' => 'Exp: AABBCCDDEEFF', 'class' => "form-control", 'id' => 'createMacAdd')) !!}
                            @error('mac_addr')
                                <script>$('#createMacAdd').css("border", "1px solid red");</script>
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-primary">Save Gateway</button>
                            <a href="{{ route('gateways.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection