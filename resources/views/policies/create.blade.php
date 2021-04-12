@extends('layouts.app')

@section('content')
<div class="container-fluid relative">
    <div class="row">
        <div class="col-lg-6">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Create New Policy:</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <form id="form">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" onInput="validatePolicyInput(this.id)" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="alert" name="alert">
                                <label class="custom-control-label" for="alert">Enabled</label>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="type">Policy Type:</label>
                            <select class="form-control" id="type" onChange="validatePolicyInput(this.id)">
                                <option selected="" disabled="">Please select...</option>
                                <option value="1">Attendance</option>
                                <option value="2">Battery</option>
                                <option value="3">Duress Button</option>
                                <option value="4">Fall</option>
                                <option value="5">Geofence</option>
                                <option value="6">Violence</option>
                            </select>
                        </div>
                        <div class="form-group" id="trigger-option-attendance" hidden>
                            <label>Attendance Option:</label>
                            <div id="radio-attendance">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="present" value="1" name="attendance-option" class="custom-control-input" onChange="validatePolicyInput(this.name)">
                                    <label class="custom-control-label" for="present"> Present</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="absent" value="0" name="attendance-option" class="custom-control-input" onChange="validatePolicyInput(this.name)">
                                    <label class="custom-control-label" for="absent"> Absent</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="trigger-option-battery" hidden>
                            <p><i class="ri-information-fill"></i> This policy will violate immediately when the battery level of the gateway or beacon is less than 20%.</p>
                        </div>
                        <div class="form-group" id="trigger-option-duress" hidden>
                            <p><i class="ri-information-fill"></i> This policy will violate immediately when the duress button at the beacon is pressed.</p>
                        </div>
                        <div class="form-group" id="trigger-option-fall" hidden>
                            <p><i class="ri-information-fill"></i> This policy will violate immediately when fall is detected.</p>
                        </div>
                        <div class="form-group" id="trigger-option-geofence" hidden>
                            <label>Geofence Option:</label>
                            <div id="radio-geofence">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="enter-zone" value="1" name="geofence-option" class="custom-control-input" onChange="validatePolicyInput(this.name)">
                                    <label class="custom-control-label" for="enter-zone"> Entering Zone</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="leave-zone" value="0" name="geofence-option" class="custom-control-input" onChange="validatePolicyInput(this.name)">
                                    <label class="custom-control-label" for="leave-zone"> Leaving Zone</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="trigger-option-violence-param" hidden>
                            <label>Violence Parameters:</label>
                            <a href="#" data-toggle="tooltip" data-placement="right" title="" style="cursor: pointer; left-padding:0" data-original-title="The policy will violate according to the G-value threashold set at the selected x, y, z-axis">
                                <i class="ri-information-fill"></i>
                            </a>
                            <div class="form-group row align-items-center ml-3" id="x-axis-row">
                                <div class="custom-control custom-checkbox custom-control col-sm-2 mt-2 mb-2">
                                    <input type="checkbox" class="custom-control-input" id="x-axis" name="x-axis-check" onChange="validatePolicyInput('violence')">
                                    <label class="custom-control-label" for="x-axis">x-axis</label>
                                </div>
                                <div class="col-sm-6" id="x-axis-div" hidden>
                                    <input type="number" min="0" max="2" data-decimals="1" step="0.1" class="form-control form-control-sm" id="x-value" placeholder="Enter g-value" onInput="validatePolicyInput(this.id)">
                                </div>
                            </div>
                            <div class="form-group row align-items-center ml-3" id="y-axis-row">
                                <div class="custom-control custom-checkbox custom-control col-sm-2 mt-2 mb-2">
                                    <input type="checkbox" class="custom-control-input" id="y-axis" name="y-axis-check" onChange="validatePolicyInput('violence')">
                                    <label class="custom-control-label" for="y-axis">y-axis</label>
                                </div>
                                <div class="col-sm-6" id="y-axis-div" hidden>
                                    <input type="number" min="0" max="2" data-decimals="1" step="0.1" class="form-control form-control-sm" id="y-value" placeholder="Enter g-value" onInput="validatePolicyInput(this.id)">
                                </div>
                            </div>
                            <div class="form-group row align-items-center ml-3" id="z-axis-row">
                                <div class="custom-control custom-checkbox custom-control col-sm-2 mt-2 mb-2">
                                    <input type="checkbox" class="custom-control-input" id="z-axis" name="z-axis-check" onChange="validatePolicyInput('violence')">
                                    <label class="custom-control-label" for="z-axis">z-axis</label>
                                </div>
                                <div class="col-sm-6" id="z-axis-div" hidden>
                                    <input type="number" min="0" max="2" data-decimals="1" step="0.1" class="form-control form-control-sm" id="z-value" placeholder="Enter g-value" onInput="validatePolicyInput(this.id)">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="trigger-option-violence-frequency" hidden>
                            <label>Violence Frequency:</label>
                            <a href="#" data-toggle="tooltip" data-placement="right" title="" style="cursor: pointer; left-padding:0" data-original-title="The policy will violate after it fulfill the parameters set for this frequency of time.">
                                <i class="ri-information-fill"></i>
                            </a>
                            <select class="form-control" id="frequency" onChange="validatePolicyInput(this.id)">
                                <option value="" selected="" disabled="">Please select ...</option>
                                <option value=1>1 second</option>
                                <option value=5>5 seconds</option>
                                <option value=10>10 seconds</option>
                                <option value=15>15 seconds</option>
                                <option value=20>20 seconds</option>
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
                                <option selected="" disabled="">Please select...</option>
                                <option value="all">Everyone</option>
                                <option value="user-only">User Only</option>
                                <option value="resident-only">Resident Only</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div class="form-group" id="custom-target-div" hidden>
                            <label for="custom-target">Custom Target(s):</label>
                            <select class="form-control" id="custom-target" onInput="validatePolicyInput(this.id)">
                                @foreach($residents as $resident)
                                    <option value="R-{{ $resident->resident_id }}">
                                    R{{ $resident->resident_id }} - {{ $resident->full_name }}</option>
                                @endforeach
                                @foreach($users as $user)
                                    <option value="U-{{ $user->user_id }}">
                                    U{{ $user->user_id }} - {{ $user->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="day">Day(s):</label>
                            <select class="form-control" id="day" onChange="validatePolicyInput(this.id)">
                                <option selected="" disabled="">Please select...</option>
                                <option value="daily">Daily</option>
                                <option value="weekdays">Monday to Friday</option>
                                <option value="weekend">Saturday to Sunday</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div class="form-group" id="custom-day-div" hidden>
                            <label for="custom-day">Custom Day(s):</label>
                            <div class="row align-items-center ml-2" id="custom-day-row">
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="sun" name="sun" onInput="validatePolicyInput('custom-day')">
                                    <label class="custom-control-label" for="sun">Sun</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="mon" name="mon" onInput="validatePolicyInput('custom-day')">
                                    <label class="custom-control-label" for="mon">Mon</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="tue" name="tue" onInput="validatePolicyInput('custom-day')">
                                    <label class="custom-control-label" for="tue">Tue</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="wed" name="wed" onInput="validatePolicyInput('custom-day')">
                                    <label class="custom-control-label" for="wed">Wed</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="thurs" name="thurs" onInput="validatePolicyInput('custom-day')">
                                    <label class="custom-control-label" for="thurs">Thurs</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="fri" name="fri" onInput="validatePolicyInput('custom-day')">
                                    <label class="custom-control-label" for="fri">Fri</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="sat" name="sat" onInput="validatePolicyInput('custom-day')">
                                    <label class="custom-control-label" for="sat">Sat</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start-time">Start Time:</label>
                            <div class="input-group date">
                                <input type="text" class="form-control" id="start-time" name="start-time" style="background-color: white" onInput="validatePolicyInput(this.id)" placeholder="Click to select time"/>
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
                            <input type="number" min="1" max="24" class="form-control" name="duration" id="duration" step="1" onInput="validatePolicyInput(this.id)" placeholder="Enter duration">
                        </div>
                        <div class="form-group mt-2">
                            <label for="location">Location(s):</label>
                            <select class="form-control" id="location" onInput="validatePolicyInput(this.id)">
                                @foreach($locations as $location)
                                    <option value="{{ $location->location_master_id }}">
                                    {{ $location->floor }}F - {{ $location->location_description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="iq-card-body d-flex justify-content-center">
                    <button type="button" id="save-btn" class="btn btn-primary m-1" onClick="savePolicy()">Save</button>
                    <a href='{{ route("policies.index") }}' id="cancel-btn" class="btn btn-secondary m-1">Cancel</a>
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
                dateFormat: "H:i",
            }
        );

        /* Initialise location select2 */
        $('#location').select2({
            multiple: true,
            closeOnSelect: false,
            allowClear: true,
            selectionCssClass: 'form-control',
            placeholder: "Please select..."
        });

        /* Reset input */
        $('#location').val('').trigger('change');
        
        /* Initialise inputSpinner for number input*/
        $('#x-value').inputSpinner();
        $('#y-value').inputSpinner();
        $('#z-value').inputSpinner();
        $('#duration').inputSpinner();
        
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
            fall: false,
            geofence: false,
            violence: false,
        };

        switch($('#type').val()){
            case "1":
                option['attendance'] = true;
                break;
            case "2":
                option['battery'] = true;
                break;
            case "3":
                option['duress'] = true;
                break;
            case "4":
                option['fall'] = true;
                break;
            case "5":
                option['geofence'] = true;
                break;
            case "6":
                option['violence'] = true;
                break;
        }
        $('#trigger-option-attendance').prop('hidden', !option['attendance']);
        $('#trigger-option-battery').prop('hidden', !option['battery']);
        $('#trigger-option-duress').prop('hidden', !option['duress']);
        $('#trigger-option-fall').prop('hidden', !option['fall']);
        $('#trigger-option-geofence').prop('hidden', !option['geofence']);
        $('#trigger-option-violence-param').prop('hidden', !option['violence']);
        $('#trigger-option-violence-frequency').prop('hidden', !option['violence']);
        
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
    function savePolicy(){
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
            case "5":
                result['geofence-option'] = validatePolicyInput('geofence-option');
                break;
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
            $('#save-btn').prop('disabled', true);
            $('#save-btn').html('<i class="fa fa-circle-o-notch fa-spin"></i>Saving');
            $.ajax({
                url: '{{ route("policies.store") }}',
                type: "POST",
                data: result,
                success:function(response){
                    let errors = response['errors'];
                    if($.isEmptyObject(response['success'])){
                        Object.keys(errors).forEach(function(key){
                            switch(key) {
                                case 'name':
                                    addInvalid('unique-name');
                                    break;
                                default:
                                    break;
                            }
                        })
                    } else {
                        $('#save-btn').css('background', 'var(--iq-success)');
                        $('#save-btn').css('border-color', 'var(--iq-success)');
                        $('#save-btn').html('<i class="fa fa-check"></i>Saved');
                        setTimeout(function() {
                            window.location.href = '{{ route("policies.index") }}';
                        }, 500);
                    }
                },
                error:function(error){
                    console.log(error);
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
                    case "weekend": 
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
            case "custom-target":
                if (!$('#invalid-custom-target').length){
                    $('#custom-target').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
                    $('#custom-target').siblings('span').after('<div class="invalid-feedback" id="invalid-' + id +'" style="display:block">Please select the target(s) for this policy.</div>')
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
            case "location":
                if (!$('#invalid-location').length){
                    $('#location').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
                    $('#location').siblings('span').after('<div class="invalid-feedback" id="invalid-' + id +'" style="display:block">Please select the location(s) for this policy.</div>')
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
                        case "type":
                            obj.after('<div class="invalid-feedback" id="invalid-' + id +'">Please select a policy type.</div>');
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
                        case "frequency":
                            obj.after('<div class="invalid-feedback" id="invalid-' + id +'">Please select the frequency.</div>');
                            break;
                        case "target":
                            obj.after('<div class="invalid-feedback" id="invalid-' + id +'">Please select the target(s) who is governed under this policy.</div>');
                            break;
                        case "day":
                            obj.after('<div class="invalid-feedback" id="invalid-' + id +'">Please select the day(s).</div>');
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
            case "location":
                if ($('#invalid-location').length){
                    $('#location').siblings('span').find('.select2-selection').css('border', '');
                    $('#invalid-location').remove();
                }
                break;
            default:
                let obj = $('#' + id);
                if (obj.hasClass('is-invalid')){
                    obj.removeClass('is-invalid');
                    $('#invalid-' + id).remove();

                    switch(id){
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