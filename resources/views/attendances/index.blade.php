@extends('layouts.app')

@section('style')
<style>
    .custom-disabled {
        opacity: 0.65;
        cursor: not-allowed;
        pointer-events: none;
    }
    .fa {
        font-size: 17px;
    }

    .li-button {
        color: var(--iq-primary); 
        text-align: center; 
        line-height: 38px; 
        display: inline-block; 
        width: 40px; 
        height: 40px; 
        -webkit-border-radius: 10px; 
        -moz-border-radius: 10px; 
        border-radius: 10px; 
        transition: all 0.3s ease-in-out; 
        transition: all 0.3s ease-in-out; 
        -moz-transition: all 0.3s ease-in-out; 
        -ms-transition: all 0.3s ease-in-out; 
        -o-transition: all 0.3s ease-in-out; 
        -webkit-transition: all 0.3s ease-in-out; 
        background: rgba(130, 122, 243, 0.2); 
        background: -moz-linear-gradient(left, rgba(130, 122, 243, 0.2) 0%, rgba(180, 122, 243, 0.2) 100%); 
        background: -webkit-gradient(left top, right top, color-stop(0%, rgba(130, 122, 243, 0.2)), color-stop(100%, rgba(180, 122, 243, 0.2))); 
        background: -webkit-linear-gradient(left, rgba(130, 122, 243, 0.2) 0%, rgba(180, 122, 243, 0.2) 100%); 
        background: -o-linear-gradient(left, rgba(130, 122, 243, 0.2) 0%, rgba(180, 122, 243, 0.2) 100%); 
        background: -ms-linear-gradient(left, rgba(130, 122, 243, 0.2) 0%, rgba(180, 122, 243, 0.2) 100%); 
        background: linear-gradient(to right, rgba(130, 122, 243, 0.2) 0%, rgba(180, 122, 243, 0.2) 100%); 
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='var(--iq-primary)', endColorstr='var(--iq-primary-light)', GradientType=1); 
        }
</style>
@endsection
@section('content')
<div class="container-fluid relative">
    <div class="row">
        <div class="col-lg-3">
            <div class="iq-card">
                <div class="iq-card-body">
                    <div class="iq-email-list">
                        <div class="iq-email-ui nav flex-column nav-pills" id="attendance-nav">
                            @foreach($attendance_policies as $policy)
                                <li class="nav-link {{ ($loop->first) ? 'active':'' }}" role="tab" data-toggle="pill" id="attendance-{{ $policy->rules_id }}" href="#tab-{{ $policy->rules_id }}">
                                    <a data-toggle="collapse" href="#collapse-{{ $policy->rules_id }}" role="button" aria-expanded="false" aria-controls="collapse-{{ $policy->rules_id }}">
                                        <i class="ri-timer-2-line"></i>
                                        <span id="name-{{ $policy->rules_id }}">{{ $policy->description }}</span>
                                        <span class="badge badge-primary badge-pill" id="absent-badge-{{ $policy->rules_id }}" {{ ($policy->absent >0) ? "":"hidden" }}>{{ $policy->absent }}</span>
                                    </a>
                                </li>
                                <div class="p-3 collapse {{ ($loop->first) ? 'show':'' }}" id="collapse-{{ $policy->rules_id }}" aria-labelledby="headingOne" data-parent="#attendance-nav">
                                    <div class="row">
                                       <div class="col-5">Detect:</div>
                                       <div class="col-7" id="type-{{ $policy->rules_id }}"><strong>{{ ($policy->attendance == 0) ? "Absent":"Present" }}</strong></div>
                                       <div class="col-5">Start Time:</div>
                                       <div class="col-7" id="start-time-{{ $policy->rules_id }}">{{ $policy->time_at_utc }}</div>
                                       <div class="col-5">Duration:</div>
                                       <div class="col-7" id="duration-{{ $policy->rules_id }}">{{ $policy->scope->duration }} hr(s)</div>
                                       <div class="col-5">Target:</div>
                                       <div class="col-7" id="target-{{ $policy->rules_id }}">{{ $policy->target_type_name }}</div>
                                       <div class="col-5">Day:</div>
                                       <div class="col-7" id="day-{{ $policy->rules_id }}">{{ ucfirst($policy->day_type) }}</div>
                                    </div>
                                    <hr class="mb-0">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 mail-box-detail">
            <div class="iq-card">
                <div class="iq-card-body p-0">
                    <div class="iq-email-to-list p-3" style="margin-bottom: -1rem!important">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <div class="iq-email-search d-flex">
                                    <form class="mr-3 position-relative">
                                        <div class="form-group">
                                            <input type="text" class="form-control" style="width:450px !important; height:45px" id="myCustomSearchBox" placeholder="Type here to search...">
                                            <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                                        </div>
                                    </form>
                                </div>
                                <div class="iq-email-search d-flex">
                                    <form class="mr-3 position-relative">
                                        <div class="form-group">
                                            <div class="input-group date">
                                                <input type="text" class="form-control" id="date" name="date" style="background-color: white; height:45px" placeholder="Click to select date"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fa fa-calendar-o"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <ul>
                                <li></li>
                                <li data-toggle="tooltip" data-placement="top" title="Reload"><a href="#" id="refresh-attendance" onClick="reloadTableData()"><i class="ri-refresh-line"></i></a></li>
                                <li data-toggle="tooltip" data-placement="top" title="Download">
                                    <div class="dropdown">
                                        <a class="li-button" id="export-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-download-line"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="export-btn" style="">
                                            <a class="dropdown-item" href="#" id="download-csv"><i class="ri-file-text-fill mr-2" ></i>CSV</a>
                                            <a class="dropdown-item" href="#" id="download-excel"><i class="ri-file-excel-fill mr-2"></i>Excel</a>
                                            <a class="dropdown-item" href="#" id="download-pdf"><i class="ri-file-pdf-fill mr-2"></i>PDF</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            
                        </div>
                    </div>
                    <div class="iq-email-listbox">
                        <div class="tab-content">
                            @foreach($attendance_policies as $policy)
                                <div class="tab-pane fade {{ ($loop->first) ? 'show active':'' }}" id="tab-{{ $policy->rules_id }}" role="tabpanel">
                                    <div class="iq-card" style="height: 100%">
                                        <div class="iq-card-body" style="padding: 15px">
                                            <div class="table-responsive" style="overflow-x: hidden">
                                                <table class="table table-stripe table-bordered hover" id="table-{{ $policy->rules_id }}">
                                                    <thead>
                                                        <tr>
                                                            <!-- <th scope="col" style="width:10%">#</th> -->
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Type</th>
                                                            <th scope="col" style="width:10%">Attendance</th>
                                                            <th scope="col">Current Location</th>
                                                            <th scope="col" style="width:25%">Detected at</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@section("script")
<script>
    let filename = '';
    let title = '';
    let message = '';

    $(function(){
        $('#date').flatpickr(
            {
                minDate: @json(explode(' ', $minDate)[0]),
                maxDate: @json(explode(' ', $maxDate)[0]),
                dateFormat: "Y-m-d",
                defaultDate: "today"
            }
        );
        let timer = setInterval(reloadTableData, 30000);
    })

    @foreach($attendance_policies as $policy)
    /* Initiate dataTable */
    let table_{{ $policy->rules_id }} = $('#table-{{ $policy->rules_id }}').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("attendance.date") }}',
            data: function(data) {
                data.rule_id = {{ $policy->rules_id }};
                data.date = $("#date").val();
            }
        },
        columns:[
            {data: 'name', checkboxes: false, orderable: true},
            {data: 'type'},
            {data: 'attendance'},
            {data: 'curr_loc'},
            {data: 'detected_at'},
        ],
        order: [[3, 'asc']],
        buttons: [
            { extend: 'csvHtml5', filename: function(){ return filename; }},
            { extend: 'excelHtml5', filename: function(){ return filename; }, title: function(){ return title; }, message: function(){ return message; } },
            { extend: 'pdfHtml5', filename: function(){ return filename; }, title: function(){ return title; }, message: function(){ return message; } },
        ]
    });
    @endforeach

    $('#myCustomSearchBox').keyup(function(){  
        let active_tab = $("div.nav li.active");
        @foreach($attendance_policies as $policy)
            table_{{ $policy->rules_id }}.search($(this).val()).draw();
        @endforeach
    })

    $('#date').on("change",function(){
        console.log('date');
        reloadTableData();
        adjustTableColumn();
    });

    $('#attendance-nav').on('shown.bs.tab', function (e) {
        let policy_id = e['target'].id.split('-')[1];

        switch(policy_id){
            @foreach($attendance_policies as $policy)
            case "{{ $policy->rules_id }}":
                table_{{ $policy->rules_id }}.columns.adjust().draw();
                break;
            @endforeach
        }
    });

    function getFileName(policy_id){
        let name = $('#name-' + policy_id).text().replace(/ /g,"_");
        let date = $("#date").val().replace(/-/g,"_");
        return date + '_Attendance_' + name;
    }

    function getTitle(policy_id){
        let name = $('#name-' + policy_id).text();
        return 'Attendance Policy: ' + name;
    }
    function getMessage(policy_id){
        let type = $('#type-' + policy_id).text();
        let start_time = $('#start-time-' + policy_id).text();
        let duration = $('#duration-' + policy_id).text();
        let target = $('#target-' + policy_id).text();
        let day = $('#day-' + policy_id).text();
        return type + '; ' + start_time + '; ' + duration + '; ' + target + '; ' + day;
    }

    $('#download-csv').on('click', function(){
        let policy_id = $('.tab-pane.show.active').prop('id').split('-')[1];
        switch(policy_id){
            @foreach($attendance_policies as $policy)
            case "{{ $policy->rules_id }}":
                filename = getFileName({{ $policy->rules_id }});
                table_{{ $policy->rules_id }}.button('.buttons-csv').trigger();
                break;
            @endforeach
        }
        notyf.success(filename+'.csv Downloaded Successfully.');
    });

    $('#download-excel').on('click', function(){
        let policy_id = $('.tab-pane.show.active').prop('id').split('-')[1];
        switch(policy_id){
            @foreach($attendance_policies as $policy)
            case "{{ $policy->rules_id }}":
                filename = getFileName({{ $policy->rules_id }});
                title = getTitle({{ $policy->rules_id }});
                message = getMessage({{ $policy->rules_id }});
                table_{{ $policy->rules_id }}.button('.buttons-excel').trigger();
                break;
            @endforeach
        }
        notyf.success(filename+'.xlsx Downloaded Successfully.');
    });

    $('#download-pdf').on('click', function(){
        let policy_id = $('.tab-pane.show.active').prop('id').split('-')[1];
        switch(policy_id){
            @foreach($attendance_policies as $policy)
            case "{{ $policy->rules_id }}":
                filename = getFileName({{ $policy->rules_id }});
                table_{{ $policy->rules_id }}.button('.buttons-pdf').trigger();
                break;
            @endforeach
        }
        notyf.success(filename+'.pdf Downloaded Successfully.');
    });

    function reloadTableData(){
        console.log('reload');
        let refresh_btn = $('#refresh-attendance');
        refresh_btn.html('<i class="fa fa-circle-o-notch fa-spin mr-0"></i>');
        refresh_btn.addClass('custom-disabled');

        @foreach($attendance_policies as $policy)
            table_{{ $policy->rules_id }}.ajax.reload();
        @endforeach

        reloadAttendanceBadge();
    }

    function adjustTableColumn(){
        @foreach($attendance_policies as $policy)
            table_{{ $policy->rules_id }}.columns.adjust().draw();
        @endforeach
    }

    function reloadAttendanceBadge(){
        let result = {
            date: $("#date").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.ajax({
            url: '{{ route("attendance.badge") }}',
            type: "GET",
            data: result,
            success:function(response){
                let errors = response['errors'];
                if($.isEmptyObject(response['success'])){
                    console.log(errors);
                } else {
                    let badge_data = response['badge_data'];
                    Object.keys(badge_data).forEach(function(key){
                        let num = badge_data[key];
                        if(num>0){
                            $('#absent-badge-' + key).html(num);
                            $('#absent-badge-' + key).prop('hidden', false);
                        } else {
                            $('#absent-badge-' + key).prop('hidden', true);
                        }
                    });

                    let refresh_btn = $('#refresh-attendance');
                    refresh_btn.html('<i class="ri-refresh-line"></i>');
                    refresh_btn.removeClass('custom-disabled');
                    notyf.success('Attendance updated successfully');
                }
            },
            error:function(error){
                console.log(error);
            }
        });
    }

    

</script>
@endsection