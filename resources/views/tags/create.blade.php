@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title"><strong>Add Beacon:</strong></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::open(['route' => 'beacons.store','method'=>'POST']) !!}
                        <div class="form-group">
                            <label for="beacon-mac">Mac Address:</label>
                            {!! Form::text('beacon_mac', null, ['placeholder' => 'Exp: AABBCCDDEEFF','class' => "form-control", 'id' => 'beacon-mac']) !!}
                            @error('beacon_mac')
                                <script>$('#beacon-mac').addClass('is-invalid');</script>
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> 
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" value="0" id="assign" name="assign" {{ $available ? '':'disabled' }}>
                                <label class="custom-control-label" for="assign">Assign to someone</label>
                                @if(!$available)
                                    <div class="text-secondary"><i class="ri-information-fill text-warning"></i> <em>Cannot assign right now. All users and residents are assigned with one beacon. </em></div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group" id="target-div" hidden>
                            {!! Form::select('target', $targetsNull, null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'target']) !!}
                            @error('target')
                                <script>
                                    $('#assign').prop("checked", true);
                                    $('#target-div').prop("hidden", false);
                                    $('#target').trigger('change');
                                </script>
                            @enderror
                        </div>
                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-primary">Save Beacon</button>
                            <a href="{{ route('beacons.index') }}" class="btn btn-secondary">Cancel</a>
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
            $('#target').select2();
            $('#target').val('').trigger('change');
            
            /* Display select2 error */
            let message = "Error Message";

            @error('target')
            message = @json($message);
            $('#target').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
            $('#target').siblings('span').after('<div class="invalid-feedback" id="invalid-target" style="display:block">'+ message +'</div>');
            @enderror
        })

        $('#assign').on('change', function(){
            if($('#assign').is(':checked')){
                if($('#target').hasClass("select2-hidden-accessible")){
                    $('#target').select2('destroy');
                }
                $('#target-div').prop('hidden', false);
                if(!$('#target').hasClass("select2-hidden-accessible")){
                    $('#target').select2();
                }
                if($('#invalid-target').length){
                    $('#invalid-target').remove();
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