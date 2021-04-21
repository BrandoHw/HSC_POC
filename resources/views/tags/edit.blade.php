@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Beacon: <strong>{{ $tag->beacon_mac }}</strong></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::model($tag, ['method' => 'PATCH', 'route' => ['beacons.update', $tag->beacon_id]]) !!}
                        <div class="form-group">
                            <label for="editMacAdd">Mac Address:</label>
                            {!! Form::text('beacon_mac', null, array('placeholder' => 'Exp: AABBCCDDEEFF','class' => "form-control", 'id' => 'editMacAdd')) !!}
                            @error('beacon_mac')
                                <script>$('#editMacAdd').css("border", "1px solid red");</script>
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" value="{{ $current ? '1':'0' }}" id="assign" name="assign" 
                                    {{ $available ? '':'disabled' }} {{ $current ? 'checked':'' }}>
                                <label class="custom-control-label" for="assign">Assign to someone</label>
                                @if(!$available)
                                    <div class="text-secondary"><i class="ri-information-fill text-warning"></i> <em>Cannot assign right now. All users and residents are assigned with one beacon. </em></div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group" id="target-div" {{ $current ? '':'hidden' }}>
                            <label for="target">Target:</label>
                            <select class="form-control" id="target" name="target">
                                @foreach($residents->sortBy('resident_fName') as $resident)
                                    <option value="R-{{ $resident->resident_id }}">
                                    R{{ $resident->resident_id }} - {{ $resident->full_name }}</option>
                                @endforeach
                                @foreach($users->sortBy('fName') as $user)
                                    <option value="U-{{ $user->user_id }}">
                                    U{{ $user->user_id }} - {{ $user->full_name }}</option>
                                @endforeach
                            </select>
                            @error('target')
                                <script>
                                    $('#assign').prop("checked", true);
                                    $('#target-div').prop("hidden", false);
                                    $('#target').val('').trigger('change');
                                </script>
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-primary">Update Beacon</button>
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

            $('#target').select2({
                selectionCssClass: 'form-control',
                placeholder: "Please select ..."
            });
            @if($available)
                @if(!empty($tag->user))
                    $('#target').select2('val', ['U-'+@json($tag->user->user_id)]);
                @elseif(!empty($tag->resident))
                    $('#target').select2('val', ['R-'+@json($tag->resident->resident_id)]);
                @else
                    $('#target').val('').trigger('change');
                @endif
            @endif
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