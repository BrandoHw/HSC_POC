@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Gateway: <strong>{{ $reader->serial }}</strong></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::model($reader, ['method' => 'PATCH','route' => ['gateways.update', $reader->gateway_id]]) !!}
                        <div class="form-group">
                            <label for="serial">Serial Number:</label>
                            {!! Form::text('serial', null, ['placeholder' => 'Exp: L1-CA-01', 'class' => "form-control", 'id' => 'serial']) !!}
                            @error('serial')
                                <script>$('#serial').addClass('is-invalid');</script>
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="mac-addr">Mac Address:</label>
                            {!! Form::text('mac_addr', null, ['placeholder' => 'Exp: AABBCCDDEEFF', 'class' => "form-control", 'id' => 'mac-addr']) !!}
                            @error('mac_addr')
                                <script>$('#mac-addr').addClass('is-invalid');</script>
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-center mt-5">
                            @can('gateway-edit')
                            <button type="submit" class="btn btn-primary">Update Gateway</button>
                            @endcan
                            <a href="{{ route('gateways.index') }}" class="btn btn-secondary">Cancel</a>
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
        @cannot('gateway-edit')
            $('#serial').prop('disabled', true);
            $('#mac-addr').prop('disabled', true);
        @endcannot
    })
</script>
@endsection