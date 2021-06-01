@extends('layouts.app')

@section('content')
<div class="container-fluid relative">
    <div class="row">
        <div class="col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Policy: <strong>{{ $policy->description }}</strong></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <form id="form">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" value="{{ $policy->description }}" id="name" onInput="validatePolicyInput(this.id)" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="alert" name="alert" {{ $policy->alert_action ? 'checked':'' }}>
                                <label class="custom-control-label" for="alert">Enabled</label>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="type">Policy Type:</label>
                            <select class="form-control" id="type" onChange="validatePolicyInput(this.id)">
                                <option {{ ($policy->rules_type_id == "1") ? 'selected':'' }} value="1">Attendance</option>
                                <option {{ ($policy->rules_type_id == "2") ? 'selected':'' }} value="2">Battery</option>
                                <option {{ ($policy->rules_type_id == "3") ? 'selected':'' }} value="3">Duress Button</option>
                                <option {{ ($policy->rules_type_id == "4") ? 'selected':'' }} value="4">Fall</option>
                                <option {{ ($policy->rules_type_id == "5") ? 'selected':'' }} value="5">Geofence</option>
                                <option {{ ($policy->rules_type_id == "6") ? 'selected':'' }} value="6">Violence</option>
                            </select>
                        </div>
                        <div class="form-group" id="trigger-option-attendance" {{ ($policy->rules_type_id == '1') ? '':'hidden' }}>
                            <label>Attendance Option:</label>
                            <div id="radio-attendance">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="present" value="1" name="attendance-option" class="custom-control-input" 
                                        onChange="validatePolicyInput(this.name)" 
                                        {{ isset($policy->attendance) ? (($policy->attendance) ? 'checked': ''):'' }}>
                                    <label class="custom-control-label" for="present"> Present</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="absent" value="0" name="attendance-option" class="custom-control-input" 
                                        onChange="validatePolicyInput(this.name)" 
                                        {{ isset($policy->attendance) ? (($policy->attendance) ? '': 'checked'):'' }}>
                                    <label class="custom-control-label" for="absent"> Absent</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="trigger-option-battery" {{ ($policy->rules_type_id == '2') ? '':'hidden' }}>
                            <label for="duration">Battery Level:</label>
                            <a href="#" data-toggle="tooltip" data-placement="right" title="This policy will violate immediately when the battery level (%) of beacon(s) is lower than this threshold." style="cursor: pointer; left-padding:0">
                                <i class="ri-information-fill"></i>
                            </a>
                            <input type="battery" data-suffix="%" min="10" max="90" class="form-control" value="{{ ($policy->battery_threshold) ? : '' }}" name="battery" id="battery" step="10" onInput="validatePolicyInput(this.id)" placeholder="Enter battery threshold">
                        </div>
                        <div class="form-group" id="trigger-option-duress" {{ ($policy->rules_type_id == '3') ? '':'hidden' }}>
                            <p><i class="ri-information-fill"></i> This policy will violate immediately when the duress button at the beacon is pressed.</p>
                        </div>
                        <div class="form-group" id="trigger-option-geofence" {{ ($policy->rules_type_id == '5') ? '':'hidden' }}>
                            <label>Geofence Option:</label>
                            <div id="radio-geofence">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="enter-zone" value="1" name="geofence-option" class="custom-control-input" 
                                        onChange="validatePolicyInput(this.name)"
                                        {{ isset($policy->geofence) ? (($policy->geofence) ? 'checked': ''):'' }}>
                                    <label class="custom-control-label" for="enter-zone"> Entering Zone</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="leave-zone" value="0" name="geofence-option" class="custom-control-input" 
                                        onChange="validatePolicyInput(this.name)"
                                        {{ isset($policy->geofence) ? (($policy->geofence) ? '': 'checked'):'' }}>
                                    <label class="custom-control-label" for="leave-zone"> Leaving Zone</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="trigger-option-fall-violence" {{ ($policy->rules_type_id == '4' || $policy->rules_type_id == '6') ? '':'hidden' }}>
                            <p><i class="ri-information-fill"></i> This policy will violate immediately if the parameters below is fulfilled.</p>
                        </div>
                        <div class="form-group" id="trigger-option-violence-param" {{ ($policy->rules_type_id == '4' || $policy->rules_type_id == '6') ? '':'hidden' }}>
                            <label><span class='policy-type-name'>{{ ($policy->rules_type_id == '4') ? 'Fall':'Violence' }}</span> Parameters:</label>
                            <a href="#" data-toggle="tooltip" data-placement="right" title="" style="cursor: pointer; left-padding:0" data-original-title="The policy will violate according to the G-value threashold set at the selected x, y, z-axis">
                                <i class="ri-information-fill"></i>
                            </a>
                            <div class="form-group row align-items-center ml-3" id="x-axis-row">
                                <div class="custom-control custom-checkbox custom-control col-sm-2 mt-2 mb-2">
                                    <input type="checkbox" class="custom-control-input" id="x-axis" name="x-axis-check" 
                                        onChange="validatePolicyInput('violence')"
                                        {{ isset($policy->x_threshold) ? 'checked':'' }}>
                                    <label class="custom-control-label" for="x-axis">x-axis</label>
                                </div>
                                <div class="col-sm-6" id="x-axis-div" {{ isset($policy->x_threshold) ? '':'hidden' }}>
                                    <input type="number" min="0" max="2" data-decimals="1" step="0.1" class="form-control form-control-sm" id="x-value" placeholder="Enter g-value" 
                                        onInput="validatePolicyInput(this.id)"
                                        value="{{ isset($policy->x_threshold) ? $policy->x_threshold:'' }}">
                                </div>
                            </div>
                            <div class="form-group row align-items-center ml-3" id="y-axis-row">
                                <div class="custom-control custom-checkbox custom-control col-sm-2 mt-2 mb-2">
                                    <input type="checkbox" class="custom-control-input" id="y-axis" name="y-axis-check" 
                                        onChange="validatePolicyInput('violence')"
                                        {{ isset($policy->y_threshold) ? 'checked':'' }}>
                                    <label class="custom-control-label" for="y-axis">y-axis</label>
                                </div>
                                <div class="col-sm-6" id="y-axis-div" {{ isset($policy->y_threshold) ? '':'hidden' }}>
                                    <input type="number" min="0" max="2" data-decimals="1" step="0.1" class="form-control form-control-sm" id="y-value" placeholder="Enter g-value" 
                                        onInput="validatePolicyInput(this.id)"
                                        value="{{ isset($policy->y_threshold) ? $policy->y_threshold:'' }}">
                                </div>
                            </div>
                            <div class="form-group row align-items-center ml-3" id="z-axis-row">
                                <div class="custom-control custom-checkbox custom-control col-sm-2 mt-2 mb-2">
                                    <input type="checkbox" class="custom-control-input" id="z-axis" name="z-axis-check" 
                                        onChange="validatePolicyInput('violence')"
                                        {{ isset($policy->z_threshold) ? 'checked':'' }}>
                                    <label class="custom-control-label" for="z-axis">z-axis</label>
                                </div>
                                <div class="col-sm-6" id="z-axis-div" {{ isset($policy->z_threshold) ? '':'hidden' }}>
                                    <input type="number" min="0" max="2" data-decimals="1" step="0.1" class="form-control form-control-sm" id="z-value" placeholder="Enter g-value" 
                                        onInput="validatePolicyInput(this.id)"
                                        value="{{ isset($policy->z_threshold) ? $policy->z_threshold:'' }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="trigger-option-frequency" {{ ($policy->rules_type_id == '4' || $policy->rules_type_id == '6') ? '':'hidden' }}>
                            <label><span class='policy-type-name'>{{ ($policy->rules_type_id == '4') ? 'Fall':'Violence' }}</span> Frequency:</label>
                            <a href="#" data-toggle="tooltip" data-placement="right" title="" style="cursor: pointer; left-padding:0" data-original-title="The policy will violate after it fulfill the parameters set for this frequency of time.">
                                <i class="ri-information-fill"></i>
                            </a>
                            <select class="form-control" id="frequency" onChange="validatePolicyInput(this.id)">
                                @if(isset($policy->frequency))
                                    <option {{ ($policy->frequency == "1") ? 'selected':'' }} value=1>1 second</option>
                                    <option {{ ($policy->frequency == "5") ? 'selected':'' }} value=5>5 seconds</option>
                                    <option {{ ($policy->frequency == "10") ? 'selected':'' }} value=10>10 seconds</option>
                                    <option {{ ($policy->frequency == "15") ? 'selected':'' }} value=15>15 seconds</option>
                                    <option {{ ($policy->frequency == "20") ? 'selected':'' }} value=20>20 seconds</option>
                                @else
                                    <option value=1>1 second</option>
                                    <option value=5>5 seconds</option>
                                    <option value=10>10 seconds</option>
                                    <option value=15>15 seconds</option>
                                    <option value=20>20 seconds</option>
                                @endif
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="iq-card">
                <div class="iq-card-body" id="scope-setting">
                    <p class="iq-bg-primary pl-3 pr-3 pt-2 pb-2 rounded">Scope Setting</p>
                    <form>
                        <div class="form-group">
                            <label for="target">Target(s):</label>
                            <select class="form-control" id="target" onChange="validatePolicyInput(this.id)">
                                <option {{ ($policy->target_type == 'all') ? 'selected':'' }} value="all">Everyone</option>
                                <option {{ ($policy->target_type == 'user-only') ? 'selected':'' }} value="user-only">User Only</option>
                                <option {{ ($policy->target_type == 'resident-only') ? 'selected':'' }} value="resident-only">Resident Only</option>
                                <option {{ ($policy->target_type == 'custom') ? 'selected':'' }} value="custom">Custom</option>
                            </select>
                        </div>
                        <div class="form-group" id="custom-target-div" {{ ($policy->target_type == 'custom') ? '':'hidden' }}>
                            <label for="custom-target">Custom Target(s):</label>
                            <select class="form-control" id="custom-target" onInput="validatePolicyInput(this.id)">
                                @foreach($residents as $resident)
                                    <option value="R-{{ $resident->resident_id }}">
                                        R{{ $resident->resident_id }} - {{ $resident->full_name }}
                                    </option>
                                @endforeach
                                @foreach($users as $user)
                                    <option value="U-{{ $user->user_id }}">
                                        U{{ $user->user_id }} - {{ $user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="day">Day(s):</label>
                            <select class="form-control" id="day" onChange="validatePolicyInput(this.id)" {{ ($policy->rules_type_id == '2') ? 'disabled':'' }}>
                                <option {{ ($policy->day_type == "daily") ? 'selected':'' }} value="daily">Daily</option>
                                <option {{ ($policy->day_type == "weekdays") ? 'selected':'' }} value="weekdays">Monday to Friday</option>
                                <option {{ ($policy->day_type == "weekends") ? 'selected':'' }} value="weekends">Saturday to Sunday</option>
                                <option {{ ($policy->day_type == "custom") ? 'selected':'' }} value="custom">Custom</option>
                            </select>
                        </div>
                        <div class="form-group" id="custom-day-div" {{ ($policy->day_type == 'custom') ? '':'hidden' }}>
                            <label for="custom-day">Custom Day(s):</label>
                            <div class="row align-items-center ml-2" id="custom-day-row">
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="sun" name="sun" 
                                        onInput="validatePolicyInput('custom-day')"
                                        {{ ($policy->day[0]) ? 'checked':'' }}>
                                    <label class="custom-control-label" for="sun">Sun</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="mon" name="mon" 
                                        onInput="validatePolicyInput('custom-day')"
                                        {{ ($policy->day[1]) ? 'checked':'' }}>
                                    <label class="custom-control-label" for="mon">Mon</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="tue" name="tue" 
                                        onInput="validatePolicyInput('custom-day')"
                                        {{ ($policy->day[2]) ? 'checked':'' }}>
                                    <label class="custom-control-label" for="tue">Tue</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="wed" name="wed" 
                                        onInput="validatePolicyInput('custom-day')"
                                        {{ ($policy->day[3]) ? 'checked':'' }}>
                                    <label class="custom-control-label" for="wed">Wed</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="thurs" name="thurs" 
                                        onInput="validatePolicyInput('custom-day')"
                                        {{ ($policy->day[4]) ? 'checked':'' }}>
                                    <label class="custom-control-label" for="thurs">Thurs</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="fri" name="fri" 
                                        onInput="validatePolicyInput('custom-day')"
                                        {{ ($policy->day[5]) ? 'checked':'' }}>
                                    <label class="custom-control-label" for="fri">Fri</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="sat" name="sat" 
                                        onInput="validatePolicyInput('custom-day')"
                                        {{ ($policy->day[6]) ? 'checked':'' }}>
                                    <label class="custom-control-label" for="sat">Sat</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start-time">Start Time:</label>
                            <div class="input-group date">
                                <input type="text" class="form-control" id="start-time" name="start-time" style="background-color: white" 
                                    onInput="validatePolicyInput(this.id)" placeholder="Click to select time"
                                    value="{{ $policy->scope->start_time }}"
                                    {{ ($policy->rules_type_id == '2') ? 'disabled':'' }}/>
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="duration">Duration (hrs):</label>
                            <a href="#" data-toggle="tooltip" data-placement="right" title="The duration of this rule being active since start time. [1 - 24 hrs]" style="cursor: pointer; left-padding:0">
                                <i class="ri-information-fill"></i>
                            </a>
                            <input type="number" min="1" max="24" class="form-control" name="duration" id="duration" step="1" 
                                onInput="validatePolicyInput(this.id)" placeholder="Enter duration"
                                value="{{ $policy->scope->duration }}"
                                {{ ($policy->rules_type_id == '2') ? 'disabled':'' }}>
                        </div>
                        <div class="form-group mt-2">
                            <label for="location">Location(s):</label>
                            <select class="form-control" id="location" onInput="validatePolicyInput(this.id)" {{ ($policy->rules_type_id == '2') ? 'disabled':'' }}>
                                @foreach($locations as $location)
                                    <option value="{{ $location->location_master_id }}">
                                    {{ $location->floor }}F - {{ $location->location_description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="iq-card-body d-flex justify-content-center">
                    @can('policy-edit')
                    <button type="button" class="btn btn-primary m-1" id="update-btn" onClick="updatePolicy()">Update Policy</button>
                    @endcan
                    <a href='{{ route("policies.index") }}' id="cancel-btn" class="btn btn-secondary m-1">Cancel</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Error-->
    <div class="modal fade" id="error-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                    <div class="container-fluid bd-example-row">
                        <div class="row justify-content-center iq-bg-danger">
                            <i class="ri-error-warning-fill text-danger" style="font-size: 85px; margin: -15px"></i>
                        </div>
                        <div class="row mt-3 justify-content-center mt-2">
                            <div class="h4 font-weight-bold" id="delete-message">An error has occured!</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="" id="delete-submessage">Contact support team or try again later.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" data-dismiss="modal">Dismiss</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@section('script')
<script type="text/javascript">
    
    $(function() {
        /* Initialize start-time timepicker */
        $('#start-time').flatpickr(
            {
                enableTime: true,
                noCalendar: true,
                dateFormat: "G:i K",
            }
        );

        /* Initialise location select2 */
        $('#location').select2({
            multiple: true,
            closeOnSelect: false,
            allowClear: true,
        });

        let selected_location = new Array(@json($policy->all_locations));
        $('#location').select2('val', selected_location);
        
        /* Initialise inputSpinner for number input*/
        $('#battery').inputSpinner();
        $('#x-value').inputSpinner();
        $('#y-value').inputSpinner();
        $('#z-value').inputSpinner();
        $('#duration').inputSpinner();

        /* Initialise select2 */
        $('#type').select2();
        $('#frequency').select2();
        $('#target').select2();
        $('#day').select2();

        @if($policy->target_type == "custom")
            /* Initialise custom-target select2 */
            if($('#target').val() == "custom"){
                $('#custom-target').select2({
                    multiple: true,
                    closeOnSelect: false,
                    scrollAfterSelect: false,
                    allowClear: true,
                });
                let selected_target = new Array(@json($policy->all_targets));
                $('#custom-target').select2('val', selected_target)
            }
        @endif

        @cannot('policy-edit')
            let disabled_items = ['name', 'alert', 'type', 'battery',
                'x-axis', 'y-axis', 'z-axis', 'x-value', 'y-value', 'z-value', 'frequency',
                'target', 'custom-target',
                'day', 'sun', 'mon', 'tue', 'wed', 'thurs', 'fri', 'sat', 
                'start-time', 'duration', 'location'];

            disabled_items.forEach(function(item){
                $('#' + item).prop('disabled', true);
            });

            $('input[name="attendance-option"]').prop('disabled', true);
            $('input[name="geofence-option"]').prop('disabled', true);
            
        @endcannot
    });

    /* If target is custom, show custom target */
    $('#target').on('change', function(){
        if($('#target').val() == "custom"){
            if($('#custom-target').hasClass("select2-hidden-accessible")){
                $('#custom-target').select2('destroy');
            }
            $('#custom-target-div').prop('hidden', false);
            if(!$('#custom-target').hasClass("select2-hidden-accessible")){
                $('#custom-target').select2({
                    multiple: true,
                    closeOnSelect: false,
                    scrollAfterSelect: false,
                    allowClear: true,
                    selectionCssClass: 'form-control',
                    placeholder: "Please select target..."
                });
            }
            $('#custom-target').val('').trigger('change');
        } else {
            $('#custom-target-div').prop('hidden', true);
        }
    })

    /* If day is custom, show custom day */
    $('#day').on('change', function(){
        /* Reset input */
        removeInvalid('custom-day');
        $('#sun').prop('checked', false);
        $('#mon').prop('checked', false);
        $('#tue').prop('checked', false);
        $('#wed').prop('checked', false);
        $('#thurs').prop('checked', false);
        $('#fri').prop('checked', false);
        $('#sat').prop('checked', false);

        /* Toggle hidden */
        if($('#day').val() == "custom"){
            $('#custom-day-div').prop('hidden', false);
        } else {
            $('#custom-day-div').prop('hidden', true);
        }
    })

    /* Toggle different configuration for different policy type */
    $('#type').on('change', function(){
        /* Reset input */
        let inputs = ['attendance-option', 'geofence-option', 'violence', 
            'x-value', 'y-value', 'z-value', 'frequency'];
        inputs.forEach(removeInvalid);

        $('input[name="attendance-option"]').prop('checked', false);
        $('#battery').val('');
        $('input[name="geofence-option"]').prop('checked', false);
        $('#x-axis').prop('checked', false);
        $('#y-axis').prop('checked', false);
        $('#z-axis').prop('checked', false);
        $('#x-value').val('');
        $('#y-value').val('');
        $('#z-value').val('');
        $('#x-axis-div').prop('hidden', true);
        $('#y-axis-div').prop('hidden', true);
        $('#z-axis-div').prop('hidden', true);
        $('#frequency').val('');

        /* Toggle hidden */
        var option = {
            attendance: false,
            battery: false,
            duress: false,
            geofence: false,
            fall_violence: false,
        };

        $('#day').prop('disabled', false);
        $('#start-time').prop('disabled', false);
        $('#duration').prop('disabled', false);
        $('#location').prop('disabled', false);

        let type = $('#type').val();

        switch(type){
            case "1":
                option['attendance'] = true;
                break;
            case "2":
                option['battery'] = true;
                $('#day').val("daily");
                $('#start-time').val("00:00");
                $('#duration').val(24);
                $('#location').val(@json($locations->pluck('location_master_id')->all())).trigger('change');

                $('#day').prop('disabled', true);
                $('#start-time').prop('disabled', true);
                $('#duration').prop('disabled', true);
                $('#location').prop('disabled', true);
                break;
            case "3":
                option['duress'] = true;
                break;
            case "4":
                $('.policy-type-name').html('Fall');
                option['fall_violence'] = true;
                break;
            case "5":
                option['geofence'] = true;
                break;
            case "6":
                $('.policy-type-name').html('Violence');
                option['fall_violence'] = true;
                break;
        }
        $('#trigger-option-attendance').prop('hidden', !option['attendance']);
        $('#trigger-option-battery').prop('hidden', !option['battery']);
        $('#trigger-option-duress').prop('hidden', !option['duress']);
        $('#trigger-option-geofence').prop('hidden', !option['geofence']);
        $('#trigger-option-fall-violence').prop('hidden', !option['fall_violence']);
        $('#trigger-option-param').prop('hidden', !option['fall_violence']);
        $('#trigger-option-frequency').prop('hidden', !option['fall_violence']);

        if(type == "4" || type == "6"){
            if($('#frequency').hasClass("select2-hidden-accessible")){
                $('#frequency').select2('destroy');
            }
            if(!$('#frequency').hasClass("select2-hidden-accessible")){
                $('#frequency').select2();
            }
        }
        
    })

    /* Toggle violence x-axis */
    $('#x-axis').on('change', function(){
        removeInvalid('x-value');
        if ($('#x-axis').is(':checked')){
            $('#x-axis-div').prop('hidden', false);
        } else{
            $('#x-axis-div').prop('hidden', true);
        }
    });

    /* Toggle violence y-axis */
    $('#y-axis').on('change', function(){
        removeInvalid('y-value');
        if ($('#y-axis').is(':checked')){
            $('#y-axis-div').prop('hidden', false);
        } else{
            $('#y-axis-div').prop('hidden', true);
        }
    });

    /* Toggle violence z-axis */
    $('#z-axis').on('change', function(){
        removeInvalid('z-value');
        if ($('#z-axis').is(':checked')){
            $('#z-axis-div').prop('hidden', false);
        } else{
            $('#z-axis-div').prop('hidden', true);
        }
    });
    

    /* Save this policy */
    function updatePolicy(){
        let items = [
            'name', 
            'type', 
            'target', 
            'day', 
            'start-time', 
            'duration', 
            'location'
        ];
        let result = {};
        items.forEach(function(item){
            result[item] = validatePolicyInput(item);
        });

        switch(result['type']){
            case "1":
                result['attendance-option'] = validatePolicyInput('attendance-option');
                break;
            case "2":
                result['battery'] = validatePolicyInput('battery');
                break
            case "5":
                result['geofence-option'] = validatePolicyInput('geofence-option');
                break;
            case "4":
            case "6":
                result['violence'] = validatePolicyInput('violence');
                result['frequency'] = validatePolicyInput('frequency');
                if(result['violence']){
                    result['x-value'] = "-1";
                    result['y-value'] = "-1";
                    result['z-value'] = "-1";
                    
                    if($('#x-axis').is(':checked')){
                        result['x-value'] = validatePolicyInput('x-value');
                    }
                    if($('#y-axis').is(':checked')){
                        result['y-value'] = validatePolicyInput('y-value');
                    }
                    if($('#z-axis').is(':checked')){
                        result['z-value'] = validatePolicyInput('z-value');
                    }
                }
                break;
        }

        if(result['target'] == 'custom'){
            result['custom-target'] = validatePolicyInput('custom-target');
        }

        if(result['day'] == -1){
            result['day'] = validatePolicyInput('custom-day');
        }

        /* Check if invalid exist */
        let invalid_exist = false;

        $.each(result, function(key, value){
            if(!value){
                invalid_exist = true;
            }
        })

        /* Add alert */
        result['alert'] = validatePolicyInput('alert');
        result['_token'] = $('meta[name="csrf-token"]').attr('content');

        console.log(result);

        if(!invalid_exist){
            $('#cancel-btn').prop('hidden', true);
            $('#update-btn').prop('disabled', true);
            $('#update-btn').html('<i class="fa fa-circle-o-notch fa-spin"></i>Updating');
            $.ajax({
                url: '{{ route("policies.update", $policy->rules_id) }}',
                type: "PATCH",
                data: result,
                success:function(response){
                    let errors = response['errors'];
                    console.log(response);
                    if($.isEmptyObject(response['success'])){
                        Object.keys(errors).forEach(function(key){
                            switch(key) {
                                case 'name':
                                    addInvalid('unique-name');
                                    break;
                                default:
                                    $('#error-modal').modal('toggle');
                                    break;
                            }
                        })
                        $('#cancel-btn').prop('hidden', false);
                        $('#update-btn').prop('disabled', false);
                        $('#update-btn').html('Update');
                    } else {
                        $('#update-btn').css('background', 'var(--iq-success)');
                        $('#update-btn').css('border-color', 'var(--iq-success)');
                        $('#update-btn').html('<i class="fa fa-check"></i>Updated');
                        setTimeout(function() {
                            window.location.href = '{{ route("policies.index") }}';
                        }, 500);
                    }
                },
                error:function(error){
                    // console.log(error);
                    $('#cancel-btn').prop('hidden', false);
                    $('#update-btn').prop('disabled', false);
                    $('#update-btn').html('Update');
                    $('#error-modal').modal('toggle');
                }
            });
        }
    }

    /* Validate Every Input*/
    function validatePolicyInput(id){
        let obj = $('#' + id);
        switch(id){
            case "type":
                removeInvalid(id);
                if(obj.val() == null){
                    addInvalid(id);
                    return false
                }
                return obj.val()
            
            case "alert":
                if(obj.is(':checked')){
                    return "1"
                } else {
                    return "0"
                }
            
            case "attendance-option":
                removeInvalid(id);
                let attendance_option = ['present', 'absent'];
                let attendance_checked = false; 
                attendance_option.forEach(function(item){
                    if($('#' + item).is(':checked')){
                        attendance_checked = true;
                    }
                })
                if(!attendance_checked){
                    addInvalid(id);
                    return false
                } else {
                    removeInvalid(id);
                    return $("input[name=attendance-option]:checked").val()
                }
            
            case "geofence-option":
                removeInvalid(id);
                let geo_option = ['enter-zone', 'leave-zone'];
                let geo_checked = false; 
                geo_option.forEach(function(item){
                    if($('#' + item).is(':checked')){
                        geo_checked = true;
                    }
                })
                if(!geo_checked){
                    addInvalid(id);
                    return false
                } else {
                    removeInvalid(id);
                    return $("input[name=geofence-option]:checked").val()
                }

            case "violence":
                removeInvalid(id);
                let violence_option = ['x-axis', 'y-axis', 'z-axis'];
                let violence_checked = false;
                violence_option.forEach(function(item){
                    if($('#' + item).is(':checked')){
                        violence_checked = true;
                    }
                })
                if(!violence_checked){
                    addInvalid(id);
                    return false
                } else {
                    removeInvalid(id);
                    return true
                }

            case "target":
                removeInvalid(id);
                if(obj.val() == null){
                    addInvalid(id);
                    return false
                }
                removeInvalid("custom-target");
                return obj.val();
            
            case "custom-target":
                removeInvalid(id);
                if(!(obj.select2('data')).length){
                    addInvalid(id);
                    return false
                }
                let selected_target = [];
                obj.select2('data').forEach(function(item){
                    selected_target.push(item['id']);
                });
                return selected_target

            case "day":
                removeInvalid(id);
                if(obj.val() == null){
                    addInvalid(id);
                    return false
                }
                removeInvalid("custom-day");

                switch(obj.val()){
                    case "daily":
                        return 127
                    case "weekdays":
                        return 62
                    case "weekends": 
                        return 65
                    case "custom":
                        return -1
                }

            case "custom-day":
                removeInvalid(id);
                let day_checked = false;
                let num = 0;
                let i = 0;
                let days = ['sat', 'fri', 'thurs', 'wed', 'tue', 'mon', 'sun'];
                days.forEach(function(item){
                    let add_on = 0;
                    if($('#' + item).is(':checked')){
                        add_on = Math.pow(2,i);
                        day_checked = true;
                    } 
                    i++;
                    num  = num + add_on;
                });

                if(!day_checked){
                    addInvalid(id);
                    return false
                } else {
                    return num
                }
            
            case "location":
                removeInvalid(id);
                if(!(obj.select2('data')).length){
                    addInvalid(id);
                    return false
                }
                let selected_loc = [];
                obj.select2('data').forEach(function(item){
                    selected_loc.push(item['id']);
                });
                return selected_loc
            
            default:
                if(!obj.val()){
                    addInvalid(id);
                    return false
                } else {
                    removeInvalid(id);
                    return obj.val()
                }
        }
    }

    /* Add invalid styling and error message */
    function addInvalid(id){
        switch(id){
            case "attendance-option":
                if (!$('#invalid-attendance-option').length){
                    $('#present').addClass('is-invalid');
                    $('#absent').addClass('is-invalid');
                    $('#radio-attendance').after('<div class="invalid-feedback" id="invalid-' + id +'" style="display:block">Please select the attendance option.</div>');
                }
                break;
            case "geofence-option":
                if (!$('#invalid-geofence-option').length){
                    $('#enter-zone').addClass('is-invalid');
                    $('#leave-zone').addClass('is-invalid');
                    $('#radio-geofence').after('<div class="invalid-feedback" id="invalid-' + id +'" style="display:block">Please select the geofence option.</div>');
                }
                break;
            case "violence":
                if (!$('#invalid-violence').length){
                    let axis = ['x-axis', 'y-axis', 'z-axis'];
                    axis.forEach(function(item){
                        $('#' + item).addClass('is-invalid');
                    });
                    $('#z-axis-row').after('<div class="invalid-feedback" id="invalid-' + id +'" style="display:block">Please select at least one axis.</div>');
                }
                break;
            case "custom-day":
                if (!$('#invalid-custom-day').length){
                    let days = ['sun', 'mon', 'tue', 'wed', 'thurs', 'fri', 'sat'];
                    days.forEach(function(item){
                        $('#' + item).addClass('is-invalid');
                    });
                    $('#custom-day-row').after('<div class="invalid-feedback" id="invalid-' + id +'" style="display:block">Please select at least one day.</div>');
                }
                break;
            case "type":
            case "frequency":
            case "target":
            case "custom-target":
            case "day":
            case "location":
                let message = "message";
                switch(id){
                    case 'type':
                        message = "Please select a policy type."
                        break;
                    case 'frequency':
                        message = "Please select the frequency."
                        break;
                    case 'target':
                        message = "Please select the target(s) who is governed under this policy."
                        break;
                    case 'custom-target':
                        message = "Please select the target(s) for this policy."
                        break;
                    case 'day':
                        message = "Please select the day(s)."
                        break;
                    case 'location':
                        message = "Please select the location(s) for this type."
                        break;
                }
                if (!$('#invalid-' + id).length){
                    $('#' + id).siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
                    $('#' + id).siblings('span').after('<div class="invalid-feedback" id="invalid-' + id +'" style="display:block">'+ message +'</div>')
                }
                break;
            case "unique-name":
                if ($('#invalid-name').length){
                    $('#invalid-name').remove();
                }
                if (!$('#name').hasClass('is-invalid')){
                    $('#name').addClass('is-invalid');
                }
                $('#name').after('<div class="invalid-feedback" id="invalid-name">This name is taken. Please enter another name.</div>')
                break;
            default:
                let obj = $('#' + id);
                if (!obj.hasClass('is-invalid')){
                    obj.addClass('is-invalid');

                    switch(id){
                        case "name":
                            obj.after('<div class="invalid-feedback" id="invalid-' + id +'">Please enter a name in the input field.</div>');
                            break;
                        case "battery":
                            $('#battery').siblings('.input-group').find('.input-group-prepend .btn').css('border-color', '#dc3545');
                            $('#battery').siblings('.input-group').find('.input-group-append .btn').css('border-color', '#dc3545');
                            $('#battery').siblings('.input-group').find('.input-group-append .input-group-text').css('border', '1px solid #dc3545');
                            $('#battery').siblings('.input-group').after('<div class="invalid-feedback" id="invalid-' + id +'">Please select the battery level threshold for this policy.</div>');
                            break;
                        case "x-value":
                            $('#x-value').siblings('.input-group').find('.input-group-prepend .btn').css('border-color', '#dc3545');
                            $('#x-value').siblings('.input-group').find('.input-group-append .btn').css('border-color', '#dc3545');
                            $('#x-value').siblings('.input-group').after('<div class="invalid-feedback" id="invalid-' + id +'">Please enter the g-value threshold for x-axis.</div>');
                            break;
                        case "y-value":
                            $('#y-value').siblings('.input-group').find('.input-group-prepend .btn').css('border-color', '#dc3545');
                            $('#y-value').siblings('.input-group').find('.input-group-append .btn').css('border-color', '#dc3545');
                            $('#y-value').siblings('.input-group').after('<div class="invalid-feedback" id="invalid-' + id +'">Please enter the g-value threshold for y-axis.</div>');
                            break;
                        case "z-value":
                            $('#z-value').siblings('.input-group').find('.input-group-prepend .btn').css('border-color', '#dc3545');
                            $('#z-value').siblings('.input-group').find('.input-group-append .btn').css('border-color', '#dc3545');
                            $('#z-value').siblings('.input-group').after('<div class="invalid-feedback" id="invalid-' + id +'">Please enter the g-value threshold for z-axis.</div>');
                            break;
                        case "start-time":
                            $('.date .input-group-append .input-group-text').css('border', '1px solid #dc3545');
                            $('.date .input-group-append .input-group-text').css('border-radius', '0 0.25rem 0.25rem 0');
                            $('#start-time').siblings('.input-group-append').after('<div class="invalid-feedback" id="invalid-' + id +'">Please select the start time for this policy.</div>');
                            break;
                        case "duration":
                            $('#duration').siblings('.input-group').find('.input-group-prepend .btn').css('border-color', '#dc3545');
                            $('#duration').siblings('.input-group').find('.input-group-append .btn').css('border-color', '#dc3545');
                            $('#duration').siblings('.input-group').after('<div class="invalid-feedback" id="invalid-' + id +'">Please select the duration for this policy.</div>');
                            break;
                    }
                }
        }
    }

    /* Remove invalid styling and error message */
    function removeInvalid(id){
        switch(id){
            case "attendance-option":
                if ($('#invalid-attendance-option').length){
                    $('#present').removeClass('is-invalid');
                    $('#absent').removeClass('is-invalid');
                    $('#invalid-attendance-option').remove();
                }
                break;
            case "geofence-option":
                if ($('#invalid-geofence-option').length){
                    $('#enter-zone').removeClass('is-invalid');
                    $('#leave-zone').removeClass('is-invalid');
                    $('#invalid-geofence-option').remove();
                }
                break;
            case "violence":
                if ($('#invalid-violence').length){
                    let axis = ['x-axis', 'y-axis', 'z-axis'];
                    axis.forEach(function(item){
                        $('#' + item).removeClass('is-invalid');
                    });
                    $('#invalid-violence').remove();
                }
                break;
            case "custom-target":
                if ($('#invalid-custom-target').length){
                    $('#custom-target').siblings('span').find('.select2-selection').css('border', '');
                    $('#invalid-custom-target').remove();
                }
                break;
            case "custom-day":
                if ($('#invalid-custom-day').length){
                    let days = ['sun', 'mon', 'tue', 'wed', 'thurs', 'fri', 'sat'];
                    days.forEach(function(item){
                        $('#' + item).removeClass('is-invalid');
                    });
                    $('#invalid-custom-day').remove();
                }
                break;
            case "type":
            case "frequency":
            case "target":
            case "custom-target":
            case "day":
            case "location":
                if ($('#invalid-' + id).length){
                    $('#' + id).siblings('span').find('.select2-selection').css('border', '');
                    $('#invalid-' + id).remove();
                }
                break;
            default:
                let obj = $('#' + id);
                if (obj.hasClass('is-invalid')){
                    obj.removeClass('is-invalid');
                    $('#invalid-' + id).remove();

                    switch(id){
                        case 'battery':
                            $('#battery').siblings('.input-group').find('.input-group-prepend .btn').css('border-color', 'var(--iq-secondary)');
                            $('#battery').siblings('.input-group').find('.input-group-append .btn').css('border-color', 'var(--iq-secondary)');
                            $('#battery').siblings('.input-group').find('.input-group-append .input-group-text').css('border', '');
                            break;
                        case 'start-time':
                            $('.date .input-group-append .input-group-text').css('border', '');
                            break;
                        case 'duration':
                            $('#duration').siblings('.input-group').find('.input-group-prepend .btn').css('border-color', 'var(--iq-secondary)');
                            $('#duration').siblings('.input-group').find('.input-group-append .btn').css('border-color', 'var(--iq-secondary)');
                            break;
                        case 'x-value':
                            $('#x-value').siblings('.input-group').find('.input-group-prepend .btn').css('border-color', 'var(--iq-secondary)');
                            $('#x-value').siblings('.input-group').find('.input-group-append .btn').css('border-color', 'var(--iq-secondary)');
                            break;
                        case 'y-value':
                            $('#y-value').siblings('.input-group').find('.input-group-prepend .btn').css('border-color', 'var(--iq-secondary)');
                            $('#y-value').siblings('.input-group').find('.input-group-append .btn').css('border-color', 'var(--iq-secondary)');
                            break;
                        case 'z-value':
                            $('#z-value').siblings('.input-group').find('.input-group-prepend .btn').css('border-color', 'var(--iq-secondary)');
                            $('#z-value').siblings('.input-group').find('.input-group-append .btn').css('border-color', 'var(--iq-secondary)');
                            break;
                    }
                }
        }
    }

</script>
@endsection