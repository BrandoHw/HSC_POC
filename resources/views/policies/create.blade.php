@extends('layouts.app')

@section('style')
<style>
    img.loader-sm {
        max-width:100%;
        max-height:100%;
        background: url('{{ asset("img/icons/loading-small.gif") }}') 25% no-repeat;
    }
    .portrait {
        height: 80px;
        width: 30px;
    }
    .email-app-details{
        position: unset;
        width: auto;
        height: auto;
    }
</style>
@endsection
@section('content')
<div class="container-fluid relative">
    <div class="row">
        <div class="col-lg-4">
            <div class="iq-card">
                <div class="iq-card-body">
                    <form>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="detail">Description:</label>
                            <textarea class="form-control" id="detail" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="type">Policy Type:</label>
                            <select class="form-control" id="type">
                                <option selected="" disabled="">Please select ...</option>
                                <option value="attendance">Attendance</option>
                                <option value="battery">Battery</option>
                                <option value="duress">Duress Button</option>
                                <option value="fall">Fall</option>
                                <option value="geofence">Geofence</option>
                                <option value="violence">Violence</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="email-app-details iq-card" id='config'>
                <div class="iq-card-body" id="trigger-setting">
                    <p class="iq-bg-primary pl-3 pr-3 pt-2 pb-2 rounded">Trigger Setting</p>
                    <form>
                        <div class="form-group" id="trigger-option-attendance" hidden>
                            <label>Attendance Option:</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="present" name="attendance-option" class="custom-control-input">
                                    <label class="custom-control-label" for="present"> Present</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="absent" name="attendance-option" class="custom-control-input">
                                    <label class="custom-control-label" for="absent"> Absent</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="trigger-option-geofence" hidden>
                            <label>Geofence Option:</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="enter-zone" name="geofence-option" class="custom-control-input">
                                    <label class="custom-control-label" for="enter-zone"> Entering Zone</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="leave-zone" name="geofence-option" class="custom-control-input">
                                    <label class="custom-control-label" for="leave-zone"> Leaving Zone</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="trigger-option-violence" hidden>
                            <label>Violence Parameters:</label>
                            <div class="row align-items-center mb-2">
                                <div class="col-2 mt-1 mb-1">
                                    <div class="custom-control custom-checkbox custom-control">
                                        <input type="checkbox" class="custom-control-input" id="x-axis" name="x-axis-check">
                                        <label class="custom-control-label" for="x-axis">x-axis</label>
                                    </div>
                                </div>
                                <div class="col-4" id="x-axis-operator-div" hidden>
                                    <select class="form-control form-control-sm" id="x-axis-operator" name="duration-format">
                                        <option selected="" disabled="">Please select operator...</option>
                                        <option value=">">Greater</option>
                                        <option value=">=">Greater and Equal</option>
                                        <option value="==">Equals</option>
                                    </select>
                                </div>
                                <div class="col-4" id="x-axis-value-div" hidden>
                                    <input type="text" class="form-control form-control-sm" name="duration-value" id="duration-value" placeholder="G-value">
                                </div>
                            </div>
                            <div class="row align-items-center mb-2">
                                <div class="col-2 mt-1 mb-1">
                                    <div class="custom-control custom-checkbox custom-control">
                                        <input type="checkbox" class="custom-control-input" id="y-axis" name="y-axis-check">
                                        <label class="custom-control-label" for="y-axis">y-axis</label>
                                    </div>
                                </div>
                                <div class="col-4" id="y-axis-operator-div" hidden>
                                    <select class="form-control form-control-sm" id="y-axis-operator" name="duration-format">
                                        <option selected="" disabled="">Please select operator...</option>
                                        <option value=">">Greater</option>
                                        <option value=">=">Greater and Equal</option>
                                        <option value="==">Equals</option>
                                    </select>
                                </div>
                                <div class="col-4" id="y-axis-value-div" hidden>
                                    <input type="text" class="form-control form-control-sm" name="duration-value" id="duration-value" placeholder="G-value">
                                </div>
                            </div>
                            <div class="row align-items-center mb-2">
                                <div class="col-2 mt-1 mb-1">
                                    <div class="custom-control custom-checkbox custom-control">
                                        <input type="checkbox" class="custom-control-input" id="z-axis" name="z-axis-check">
                                        <label class="custom-control-label" for="z-axis">z-axis</label>
                                    </div>
                                </div>
                                <div class="col-4" id="z-axis-operator-div" hidden>
                                    <select class="form-control form-control-sm" id="z-axis-operator" name="duration-format">
                                        <option selected="" disabled="">Please select operator...</option>
                                        <option value=">">Greater</option>
                                        <option value=">=">Greater and Equal</option>
                                        <option value="==">Equals</option>
                                    </select>
                                </div>
                                <div class="col-4" id="z-axis-value-div" hidden>
                                    <input type="text" class="form-control form-control-sm" name="duration-value" id="duration-value" placeholder="G-value">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="trigger-duration" hidden>
                            <labe><span class="text-dark"> Duration: </span><span class="font-italic text-secondary" id="duration-label">(Detected)</span></label>
                            <a href="#" data-toggle="tooltip" id="duration-tooltip" data-placement="right" title="The amount of time allowed before a violation is triggered." style="cursor: pointer;">
                                <i class="ri-information-line"></i>
                            </a>
                            <div class="row" id="duration-config">
                                <div class="col">
                                    <input type="text" class="form-control" name="duration-value" id="duration-value" placeholder="Value">
                                </div>
                                <div class="col">
                                    <select class="form-control" id="duration-format" name="duration-format">
                                        <option value="second">Second(s)</option>
                                        <option value="hour">Minute(s)</option>
                                        <option value="day">Hour(s)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="trigger-specific-time" hidden>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="specific-time" name="specific-time-check">
                                <label class="custom-control-label" for="specific-time">Only within specific time</label>
                            </div>
                            <div class="row mt-2" id="specific-time-config" hidden>
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
                    </form>
                </div>
                <hr class="mt-0 mb-0" id="line">
                <div class="iq-card-body" id="location-setting" hidden>
                    <p class="iq-bg-primary pl-3 pr-3 pt-2 pb-2 rounded">Location Setting</p>
                    <form>
                        <div class="form-group">
                            <label for="loc">Location:</label>
                            <select class="form-control" id="loc">
                                <option selected="" disabled="">Please select ...</option>
                                <option value="floor-1">First Floor</option>
                                <option value="floor-2">Second Floor</option>
                                <option value="floor-3">Third Floor</option>
                                <option value="floor-4">Forth Floor</option>
                            </select>
                            <div id="loc-img-container" class="potrait mt-3" hidden>
                                <img id="asyncImg" class='loader-sm'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="zone">Zone:</label>
                            <select class="form-control" id="zone">
                            </select>
                        </div>
                    </form>
                </div>
                <div class="iq-card-body d-flex justify-content-center">
                    <a href='{{ route("policies.index") }}' class="btn btn-primary m-1">Submit</a>
                    <a href='{{ route("policies.index") }}' class="btn btn-secondary m-1">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@section('script')
<script type="text/javascript">
    var dTable;
    
    $(function() {
        $('#time-start').flatpickr(
            {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
            }
        );
        $('#time-end').flatpickr(
            {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
            }
        );
    });

    // Display different configuration for different policy type
    $('#type').on('change', function(){
        if(!$('#config').hasClass('show')){
            $('#config').toggleClass('show');
        }
        var option = {
            attendance: false,
            geofence: false,
            violence: false,
            duration: true,
            specific: true,
            location: true,
        };

        $('#duration-label').html('(After detection)');
        $('#duration-tooltip').attr('title', 'The amount of time allowed before a violation is triggered.');
        $('#line').prop('hidden', true);
        switch($(this).val()){
            case "attendance":
                option['attendance'] = true;
                break;
            case "battery":
                option['specific_time'] = false;
                option['location'] = false;
                break;
            case "duress":
                option['specific'] = false;
                option['location'] = false;
                $('#duration-label').html('(To be ignored)');
                $('#duration-tooltip').attr('title', 'The amount of time after which the violations will be ignored.');
                break;
            case "fall":
                option['specific'] = false;
                option['location'] = false;
                break;
            case "geofence":
                option['geofence'] = true;
                $('#line').prop('hidden', false);
                break;
            case "violence":
                option['violence'] = true;
                option['specific'] = false;
                break;
        }
        $('#trigger-option-attendance').prop('hidden', !option['attendance']);
        $('#trigger-option-geofence').prop('hidden', !option['geofence']);
        $('#trigger-option-violence').prop('hidden', !option['violence']);
        $('#trigger-duration').prop('hidden', !option['duration']);
        $('#trigger-specific-time').prop('hidden', !option['specific']);
        $('#location-setting').prop('hidden', !option['location']);

    })

    // Select violence axis
    $('#x-axis').on('change', function(){
        if ($(this).is(':checked')){
            $('#x-axis-operator-div').prop('hidden', false);
            $('#x-axis-value-div').prop('hidden', false);
        } else{
            $('#x-axis-operator-div').prop('hidden', true);
            $('#x-axis-value-div').prop('hidden', true);
        }
    });

    $('#y-axis').on('change', function(){
        if ($(this).is(':checked')){
            $('#y-axis-operator-div').prop('hidden', false);
            $('#y-axis-value-div').prop('hidden', false);
        } else{
            $('#y-axis-operator-div').prop('hidden', true);
            $('#y-axis-value-div').prop('hidden', true);
        }
    });

    $('#z-axis').on('change', function(){
        if ($(this).is(':checked')){
            $('#z-axis-operator-div').prop('hidden', false);
            $('#z-axis-value-div').prop('hidden', false);
        } else{
            $('#z-axis-operator-div').prop('hidden', true);
            $('#z-axis-value-div').prop('hidden', true);
        }
    });

    // Select location
    $('#loc').on('change', function(){
        $('#loc-img-container').removeAttr('hidden');

        var i = 0;
        $('.loader-sm').on('load', function(i){
            while(i < 1){
                $('#asyncImg').attr('src', $(this).attr('src'));
                i++;
            }
            return false;
        });

        var formatOption = function(data){
            var result = $('<span><i class="ri-bookmark-fill" style="color:' + data.color +'"></i> '+ data.text +'</span>');
            return result
        }

        var formatSelected = function(result){
            var selected = $('<span><i class="ri-bookmark-fill" style="color:' + result.color +'"></i> '+ result.text +'</span>');
            return selected
        }

        $('#zone').empty();
        var data;

        switch($(this).val()){
            case "floor-1":
                $('.loader-sm').attr('src', '{{ asset("images/floorplan-edit.png") }}');   
                data = [
                    {
                        id: 0,
                        text: 'Entire Area',
                        color: 'Grey'
                    },
                    {
                        id: 1,
                        text: 'Living Room',
                        color: 'Orange'
                    },
                    {
                        id: 2,
                        text: 'Room 101',
                        color: 'Purple'
                    },
                ];
                break;
            case "floor-2":
                $('.loader-sm').attr('src', '{{ asset("images/floorplan2-edit.png") }}');   
                data = [
                    {
                        id: 0,
                        text: 'Entire Area',
                        color: 'Grey'
                    },
                    {
                        id: 1,
                        text: 'Living Room',
                        color: 'Orange'
                    },
                    {
                        id: 2,
                        text: 'Bathroom',
                        color: 'Green'
                    },
                    {
                        id: 3,
                        text: 'Bedroom B',
                        color: 'Blue'
                    },
                ];
                break;
            case "floor-3":
                $('.loader-sm').attr('src', '{{ asset("images/floorplan3-edit.png") }}');   
                data = [
                    {
                        id: 0,
                        text: 'Entire Area',
                        color: 'Grey'
                    },
                    {
                        id: 1,
                        text: 'Bedroom',
                        color: 'Purple'
                    },
                    {
                        id: 2,
                        text: 'Kitchen',
                        color: 'Blue'
                    },
                    {
                        id: 3,
                        text: 'Dining Are',
                        color: 'Orange'
                    },
                ];
                break;
            case "floor-4":
                $('.loader-sm').attr('src', '{{ asset("images/floorplan4-edit.png") }}');   
                data = [
                    {
                        id: 0,
                        text: 'Entire Area',
                        color: 'Grey'
                    }
                ];
                break;
        }
        $('#zone').select2({
            data:data,
            templateResult: formatOption,
            templateSelection: formatSelected,
            placeholder: "Please select ...",
            theme: 'classic',
        });
    });

    $('#specific-time').on('change', function(){
        if ($(this).is(':checked')){
            $('#specific-time-config').prop('hidden', false);
        } else{
            $('#specific-time-config').prop('hidden', true);
        }
    });


</script>
@endsection