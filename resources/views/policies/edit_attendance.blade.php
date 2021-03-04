@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Policy ID: {{ $policy->rules_id }}</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    {!! Form::model($policy, ['method' => 'PATCH', 'route' => ['policies.update', $policy->rules_id]]) !!}
                        <div class="form-group">
                            <label for="viewName">Name:</label>
                            {!! Form::text('description', null, array('class' => "form-control", 'id' => 'viewName')) !!}
                            @error('description')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="viewLName">Policy Type:</label>
                            {!! Form::text('rules_type_id', $policy->policyType->rules_type_desc, array('class' => "form-control", 'id' => 'viewType', 'readonly')) !!}
                            @error('rules_type_id')
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Attendance Option:</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="present" name="attendance-option" class="custom-control-input" {{ ($policy->attendance == 1) ? "checked":""}}>
                                    <label class="custom-control-label" for="present"> Present</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="absent" name="attendance-option" class="custom-control-input" {{ ($policy->attendance == 0) ? "checked":""}}>
                                    <label class="custom-control-label" for="absent"> Absent</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Time Option:</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="all-day" name="time-option" class="custom-control-input" {{ ($policy->scope_id == 4) ? "checked":""}}>
                                    <label class="custom-control-label" for="all-day"> All day</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="specific-time" name="time-option" class="custom-control-input" {{ ($policy->scope_id != 4) ? "checked":""}}>
                                    <label class="custom-control-label" for="specific-time"> Only within specific time</label>
                                </div>
                            </div>
                            <div class="row mt-2" id="specific-time-config" {{ ($policy->scope_id == 4) ? "hidden":""}}>
                                <div class="col">
                                    <label>Start Time:</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control" id="time-start" name="time-start" style="background-color: white"/>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label>End Time:</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control" id="time-end" name="time-end" style="background-color: white"/>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    @error('beacon_id')<script>$('#editTag').attr('style', 'border: 1px solid red !important');</script>@enderror

    <script>
        $(function(){
            $('#editTag').select2({
                placeholder: "Please select ..."
            });

            $('#time-start').flatpickr(
                {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    defaultDate: @json($policy->scope->start_time)
                }
            );
            $('#time-end').flatpickr(
                {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    defaultDate: @json($policy->scope->end_time)
                }
            );
        });

        $('#specific-time').on('change', function(){
            if ($(this).is(':checked')){
                $('#specific-time-config').prop('hidden', false);
            } else{
                $('#specific-time-config').prop('hidden', true);
            }
        });

        $('#all-day').on('change', function(){
        if ($(this).is(':checked')){
            $('#specific-time-config').prop('hidden', true);
        } else{
            $('#specific-time-config').prop('hidden', false);
        }
    });
    </script>
@endsection