@extends('layouts.app')

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
                                        <i class="ri-timer-2-line"></i>{{ $policy->description }}
                                        @php($now = \Carbon\Carbon::now()->toDateTimeString())
                                        @php($start_time = \Carbon\Carbon::parse($policy->datetime_at_utc))
                                        @if($now >= $start_time)
                                            @if($policy->attendance == 0)
                                                @php($absent = $policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count())
                                            @else
                                                @php($absent = count($policy->all_targets) - ($policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count()))
                                            @endif
                                            @if($absent > 0)
                                                <span class="badge badge-primary badge-pill">{{ $absent }}</span>
                                            @endif
                                        @endif
                                    </a>
                                </li>
                                <div class="p-3 collapse {{ ($loop->first) ? 'show':'' }}" id="collapse-{{ $policy->rules_id }}" aria-labelledby="headingOne" data-parent="#attendance-nav">
                                    <div class="row">
                                       <div class="col-5">Detect:</div>
                                       <div class="col-7"><strong>{{ ($policy->attendance == 0) ? "Absent":"Present" }}</strong></div>
                                       <div class="col-5">Start Time:</div>
                                       <div class="col-7">{{ \Carbon\Carbon::parse($policy->scope->start_time)->format('g:i A') }}</div>
                                       <div class="col-5">Duration:</div>
                                       <div class="col-7">{{ $policy->scope->duration }} hr(s)</div>
                                       <div class="col-5">Target:</div>
                                       <div class="col-7">{{ $policy->target_type }}</div>
                                       <div class="col-5">Day:</div>
                                       <div class="col-7">{{ $policy->day_type }}</div>
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
                                <li data-toggle="tooltip" data-placement="top" title="Reload"><a href="#"><i class="ri-restart-line"></i></a></li>
                                <li data-toggle="tooltip" data-placement="top" title="Download"><a href="#"><i class="ri-download-line"></i></a></li>
                            </ul>
                            
                        </div>
                    </div>
                    <div class="iq-email-listbox">
                        <div class="tab-content">
                            @foreach($attendance_policies as $policy)
                                <div class="tab-pane fade {{ ($loop->first) ? 'show active':'' }}" id="tab-{{ $policy->rules_id }}" role="tabpanel">
                                    <div class="iq-card" style="height: 100%">
                                        <div class="iq-card-body">
                                            <div class="table-responsive">
                                                <table class="table table-stripe table-bordered hover" id="table-{{ $policy->rules_id }}">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" style="width:10%">#</th>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Type</th>
                                                            <th scope="col">Attendance</th>
                                                            <th scope="col">Current Location</th>
                                                            <th scope="col">Detected at</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($policy->scope->tags as $target)
                                                            @if($target->is_assigned == true)
                                                                <tr href="#" id="target-{{ $target->beacon_id }}">
                                                                    <td>{{ $target->beacon_id }}</td>
                                                                    <td>
                                                                        @if($target->beacon_type == 2)
                                                                            {{ $target->user->full_name ?? '-' }}
                                                                        @else
                                                                            {{ $target->resident->full_name ?? '-' }}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($target->beacon_type == 2)
                                                                            Staff
                                                                        @else
                                                                            Resident
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @php($start_time = \Carbon\Carbon::parse($policy->datetime_at_utc))
                                                                        @if($now < $start_time)
                                                                            <span class="badge badge-pill badge-secondary">N/A</span>
                                                                        @else
                                                                            @if($policy->attendance == 0)
                                                                                @php($found_absent_last = $attendance_alerts->where('rules_id', $policy->rules_id)
                                                                                ->where('beacon_id', $target->beacon_id)
                                                                                ->where('occured_at', '>=', date($policy->datetime_at_utc))
                                                                                ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                                                                                ->last())
                                                                                <span class="badge badge-pill badge-{{ (isset($found_absent_last)) ? 'danger':'success'}}">
                                                                                    {{ (isset($found_absent_last)) ? 'Absent':'Present'}}
                                                                                </span>
                                                                            @else
                                                                                @php($found_present_first = $attendance_alerts->where('rules_id', $policy->rules_id)
                                                                                ->where('beacon_id', $target->beacon_id)
                                                                                ->where('occured_at', '>=', date($policy->datetime_at_utc))
                                                                                ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                                                                                ->first())
                                                                                <span class="badge badge-pill badge-{{ (isset($found_present_first)) ? 'success':'danger'}}">
                                                                                    {{ (isset($found_present_first)) ? 'Present':'Absent'}}
                                                                                </span>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        {{ $target->current_location ?? '-' }}
                                                                    </td>
                                                                    <td>
                                                                        @if($policy->attendance == 0)
                                                                            {{ $found_absent_last->occured_at_tz ?? '-' }}
                                                                        @else
                                                                            {{ $found_present_first->occured_at_tz ?? '-' }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
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
    $(function(){
        $('#date').flatpickr(
            {
                minDate: @json(explode(' ', $minDate)[0]),
                maxDate: @json(explode(' ', $maxDate)[0]),
                dateFormat: "Y-m-d",
                defaultDate: "today"
            }
        );
    })

    @foreach($attendance_policies as $policy)
    /* Initiate dataTable */
    let table_{{ $policy->rules_id }} = $('#table-{{ $policy->rules_id }}').DataTable({
        order: [[3, 'asc']],
    });
    @endforeach

    $('#myCustomSearchBox').keyup(function(){  
        let active_tab = $("div.nav li.active");
        // switch(active_tab.attr('id')){
        //     @foreach($attendance_policies as $policy)
        //         case "attendance-{{ $policy->rules_id }}":
        //             table_{{ $policy->rules_id }}.search($(this).val()).draw();
        //             break;
        //     @endforeach
        // }
        @foreach($attendance_policies as $policy)
            table_{{ $policy->rules_id }}.search($(this).val()).draw();
        @endforeach
    })

    $('#alertTable tbody tr td:not(:first-child)').click(function () {
        window.location.href = $(this).parent('tr').attr('href');
    });
</script>
@endsection