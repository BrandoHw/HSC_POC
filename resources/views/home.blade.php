@extends('layouts.app')

@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
               <div class="row">
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-lg-0">
                        <div class="rounded-circle iq-card-icon iq-bg-primary mr-3"> <i class="ri-message-line"></i></div>
                        <div class="text-left">
                           <h4>{{ $policies->count() }}</h4>
                           <p class="mb-0">Total Policies</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-lg-0">
                        <div class="rounded-circle iq-card-icon iq-bg-success mr-3"> <i class="ri-base-station-line"></i></div>
                        <div class="text-left">
                           <h4>{{ $readers->count() }}</h4>
                           <p class="mb-0">Total Gateways</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="rounded-circle iq-card-icon iq-bg-info mr-3"> <i class="ri-share-line"></i></div>
                        <div class="text-left">
                           <h4>{{ $tags->count() }}</h4>
                           <p class="mb-0">Total Beacons</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="rounded-circle iq-card-icon iq-bg-warning mr-3"> <i class="ri-group-line"></i></div>
                        <div class="text-left">
                           <h4>{{ $residents->count() }}</h4>
                           <p class="mb-0">Total Residents</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="rounded-circle iq-card-icon iq-bg-danger mr-3"> <i class="ri-alarm-warning-line"></i></div>
                        <div class="text-left">
                           <h4>{{ $alerts->count() }}</h4>
                           <p class="mb-0">Total Alerts</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
               @include ("map.dashboard")
            </div>
         </div>
      </div>
      <div class="col-lg-8">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h4 class="card-title">Today's Attendance</h4>
               </div>
               <div class="iq-card-header-toolbar d-flex align-items-center">
                  <div class="dropdown">
                     <a href="{{ route('attendance.index') }}">View All</a>
                     <!-- <span class="dropdown-toggle" id="dropdownMenuButton-five" data-toggle="dropdown">
                     </span> -->
                  </div>
               </div>
            </div>
            <div class="iq-card-body">
               <div>
                  <ul class="nav nav-tabs justify-content-right" id="myTab-2" role="tablist">
                        @foreach($attendance_policies as $policy)
                        <li class="nav-item">
                           <a class="nav-link {{ $loop->first ? 'active':'' }}" id="attendance-{{ $policy->rules_id }}" data-toggle="tab" href="#tab-{{ $policy->rules_id }}" role="tab" aria-selected="true">{{ $policy->description }}</a>
                        </li>
                        @endforeach
                  </ul>
                  <div class="tab-content">
                     @foreach($attendance_policies as $policy)
                     <div class="tab-pane fade {{ ($loop->first) ? 'show active':'' }}" id="tab-{{ $policy->rules_id }}" role="tabpanel">
                        <div class="iq-card" style="height: 100%">
                           <div class="iq-card-body">
                              <div class="table-responsive">
                                 <table class="table mb-0 table-borderless">
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
                                       @foreach ($policy->scope->tags->sortByDesc('updated_at')->take(5) as $target)
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
                                                      @php($now = \Carbon\Carbon::now()->toDateTimeString())
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
               <!-- <div class="table-responsive">
                  <table class="table mb-0 table-borderless">
                     <tbody>
                        <tr>
                           <td class="text-center">
                              <img class="rounded-circle img-fluid avatar-40" src="images/user/01.jpg" alt="profile">
                           </td>
                           <td>Anna Sthesia</td>
                           <td>
                              <div class="badge badge-pill badge-cyan">Frontend Developer</div>
                           </td>
                           <td>600 Courses</td>
                           <td>200 Followers</td>
                           <td><i class="ri-star-s-fill text-warning"></i> 5.0</td>
                        </tr>
                        <tr>
                           <td class="text-center">
                              <img class="rounded-circle img-fluid avatar-40" src="images/user/02.jpg" alt="profile">
                           </td>
                           <td>Brock Lee</td>
                           <td>
                              <div class="badge badge-pill badge-cobalt-blue text-white">UI/UX Design</div>
                           </td>
                           <td>800 Courses</td>
                           <td>780 Followers</td>
                           <td><i class="ri-star-s-fill text-warning"></i> 4.5</td>
                        </tr>
                        <tr>
                           <td class="text-center">
                              <img class="rounded-circle img-fluid avatar-40" src="images/user/03.jpg" alt="profile">
                           </td>
                           <td>Dan Druff</td>
                           <td>
                              <div class="badge badge-pill badge-spring-green">Backend Developer</div>
                           </td>
                           <td>300 Courses</td>
                           <td>800 Followers</td>
                           <td><i class="ri-star-s-fill text-warning"></i> 3.8</td>
                        </tr>
                        <tr>
                           <td class="text-center">
                              <img class="rounded-circle img-fluid avatar-40" src="images/user/04.jpg" alt="profile">
                           </td>
                           <td>Lynn Guini</td>
                           <td>
                              <div class="badge badge-pill badge-amber">Wordpress Developer</div>
                           </td>
                           <td>550 Courses</td>
                           <td>300 Followers</td>
                           <td><i class="ri-star-s-fill text-warning"></i> 4.8</td>
                        </tr>
                        <tr>
                           <td class="text-center">
                              <img class="rounded-circle img-fluid avatar-40" src="images/user/05.jpg" alt="profile">
                           </td>
                           <td>Eric Shun</td>
                           <td>
                              <div class="badge badge-pill badge-pink">Web designer</div>
                           </td>
                           <td>690 Courses</td>
                           <td>480 Followers</td>
                           <td><i class="ri-star-s-fill text-warning"></i> 5.0</td>
                        </tr>
                     </tbody>
                  </table>
               </div> -->
            </div>
         </div>
      </div>
      <div class="col-lg-4">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h4 class="card-title">Attendance</h4>
               </div>
            </div>
            <div class="iq-card-body">
               <div id="home-perfomer-chart"></div>
            </div>
         </div>
      </div>
   </div>
</div>


@endsection

@section('extra')
<div class="iq-right-fixed">
   <div class="iq-card" style="box-shadow: none;">
      <div class="chat-search pt-3 pl-3">
         <div class="d-flex align-items-center">
            <h4>Events</h4>
         </div>
      </div>
      <div class="chat-sidebar-channel scroller mt-4 pl-3" style="height:calc(100vh - 5rem)">
         @if(count($alerts) < 1)
            <div class="text-center align-middle" style="margin-top: 12rem">
               <div data-icon="x" style="font-size: 85px" class="icon text-secondary" ></div>
               <p>No Alert Right Now!</p>
            </div>
         @else
            <ul class="iq-timeline" id="activity">
               @foreach($alerts as $alert)
                  <li class="li-alert active">
                     <div class="timeline-dots border-danger"></div>
                        <h6 class="float-left mb-1 text-danger">{{ $alert->policy->description }}</h6>
                        <small class="float-right mt-1">{{ $alert->occured_at_tz }}</small>
                        <div class="d-flex w-100 mb-1 align-items-center">
                           <div class="user-img img-fluid"><img src="{{ asset('img/avatars/default-profile-m.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                           <div class="media-support-info ml-3">
                              <h6>{{ ($alert->tag->beacon_type == 1) ? $alert->tag->resident->full_name:$alert->tag->user->full_name }}</h6>
                              <p class="badge bagde-pill badge-danger mb-0 font-size-12">{{ $alert->reader->location_full }}</p>
                           </div>
                           <div class="text-danger small"><i class="ri-alert-fill"></i>{{ $alert->policy->policyType->rules_type_desc }}</div>
                        </div>
                  </li>
               @endforeach
            </ul>      
         @endif
         <!-- <ul class="iq-timeline" id="activity">
            <li class="li-alert active">
               <div class="timeline-dots border-danger"></div>
                  <h6 class="float-left mb-1 text-danger">Fall 1</h6>
                  <small class="float-right mt-1">10:00AM, Today</small>
                  <div class="d-flex w-100 mb-1 align-items-center">
                     <div class="user-img img-fluid"><img src="{{ asset('template/images/user/03.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                     <div class="media-support-info ml-3">
                        <h6>Lynn Guini</h6>
                        <p class="badge bagde-pill badge-danger mb-0 font-size-12">Toilet 1</p>
                     </div>
                     <div class="iq-card-header-toolbar">
                        <div class="text-danger h6"><i class="ri-alert-fill"></i> Fall</div>
                     </div>
                  </div>
            </li>
            <li class="li-alert active">
                  <div class="timeline-dots border-danger"></div>
                  <h6 class="float-left mb-1 text-danger">Violence 1</h6>
                  <small class="float-right mt-1">10:00AM, Today</small>
                  <div class="d-flex w-100 mb-1 align-items-center">
                     <div class="user-img img-fluid"><img src="{{ asset('template/images/user/03.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                     <div class="media-support-info ml-3">
                        <h6>Lynn Guini</h6>
                        <p class="badge bagde-pill badge-danger mb-0 font-size-12">Common Area 1</p>
                     </div>
                     <div class="iq-card-header-toolbar">
                        <div class="text-danger h6"><i class="ri-alert-fill"></i> Violence</div>
                     </div>
                  </div>
            </li>
            <li class="li-notif">
                  <div class="timeline-dots border-success"></div>
                  <h6 class="float-left mb-1">Lobby</h6>
                  <small class="float-right mt-1">10:00AM, Today</small>
                  <div class="d-flex w-100 mb-1 align-items-center">
                     <div class="user-img img-fluid"><img src="{{ asset('template/images/user/03.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                     <div class="media-support-info ml-3">
                        <h6>Lynn Guini</h6>
                     </div>
                     <div class="iq-card-header-toolbar">
                        <div class="text-success h6"><i class="ri-information-fill"></i> Entered</div>
                     </div>
                  </div>
            </li>
            <li class="li-alert active">
                  <div class="timeline-dots border-danger"></div>
                  <h6 class="float-left mb-1 text-danger">Violence 1</h6>
                  <small class="float-right mt-1">10:00AM, Today</small>
                  <div class="d-flex w-100 mb-1 align-items-center">
                     <div class="user-img img-fluid"><img src="{{ asset('template/images/user/03.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                     <div class="media-support-info ml-3">
                        <h6>Lynn Guini</h6>
                        <p class="badge bagde-pill badge-danger mb-0 font-size-12">Common Area 1</p>
                     </div>
                     <div class="iq-card-header-toolbar">
                        <div class="text-danger h6"><i class="ri-alert-fill"></i> Violence</div>
                     </div>
                  </div>
            </li>
            <li class="li-notif">
                  <div class="timeline-dots border-success"></div>
                  <h6 class="float-left mb-1">Lobby</h6>
                  <small class="float-right mt-1">10:00AM, Today</small>
                  <div class="d-flex w-100 mb-1 align-items-center">
                     <div class="user-img img-fluid"><img src="{{ asset('template/images/user/03.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                     <div class="media-support-info ml-3">
                        <h6>Lynn Guini</h6>
                     </div>
                     <div class="iq-card-header-toolbar">
                        <div class="text-success h6"><i class="ri-information-fill"></i> Entered</div>
                     </div>
                  </div>
            </li>
            <li class="li-alert active">
                  <div class="timeline-dots border-danger"></div>
                  <h6 class="float-left mb-1 text-danger">Violence 1</h6>
                  <small class="float-right mt-1">10:00AM, Today</small>
                  <div class="d-flex w-100 mb-1 align-items-center">
                     <div class="user-img img-fluid"><img src="{{ asset('template/images/user/03.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                     <div class="media-support-info ml-3">
                        <h6>Lynn Guini</h6>
                        <p class="badge bagde-pill badge-danger mb-0 font-size-12">Common Area 1</p>
                     </div>
                     <div class="iq-card-header-toolbar">
                        <div class="text-danger h6"><i class="ri-alert-fill"></i> Violence</div>
                     </div>
                  </div>
            </li>
            <li class="li-alert active">
                  <div class="timeline-dots border-danger"></div>
                  <h6 class="float-left mb-1 text-danger">Resident Dupress</h6>
                  <small class="float-right mt-1">10:00AM, Today</small>
                  <div class="d-flex w-100 mb-1 align-items-center">
                     <div class="user-img img-fluid"><img src="{{ asset('template/images/user/03.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                     <div class="media-support-info ml-3">
                        <h6>Lynn Guini</h6>
                        <p class="badge bagde-pill badge-danger mb-0 font-size-12">Room 103</p>
                     </div>
                     <div class="iq-card-header-toolbar">
                        <div class="text-danger h6"><i class="ri-alert-fill"></i> SOS</div>
                     </div>
                  </div>
            </li>
            <li class="li-notif">
                  <div class="timeline-dots border-success"></div>
                  <h6 class="float-left mb-1">Lobby</h6>
                  <small class="float-right mt-1">10:00AM, Today</small>
                  <div class="d-flex w-100 mb-1 align-items-center">
                     <div class="user-img img-fluid"><img src="{{ asset('template/images/user/03.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                     <div class="media-support-info ml-3">
                        <h6>Lynn Guini</h6>
                     </div>
                     <div class="iq-card-header-toolbar">
                        <div class="text-success h6"><i class="ri-information-fill"></i> Left</div>
                     </div>
                  </div>
            </li>
            <li class="li-notif">
                  <div class="timeline-dots border-success"></div>
                  <h6 class="float-left mb-1">Lobby</h6>
                  <small class="float-right mt-1">10:00AM, Today</small>
                  <div class="d-flex w-100 mb-1 align-items-center">
                     <div class="user-img img-fluid"><img src="{{ asset('template/images/user/03.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                     <div class="media-support-info ml-3">
                        <h6>Lynn Guini</h6>
                     </div>
                     <div class="iq-card-header-toolbar">
                        <div class="text-success h6"><i class="ri-information-fill"></i> Entered</div>
                     </div>
                  </div>
            </li>
            <li class="li-alert active">
                  <div class="timeline-dots border-danger"></div>
                  <h6 class="float-left mb-1 text-danger">Inactivity 30 Minutes</h6>
                  <small class="float-right mt-1">10:00AM, Today</small>
                  <div class="d-flex w-100 mb-1 align-items-center">
                     <div class="user-img img-fluid"><img src="{{ asset('template/images/user/03.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                     <div class="media-support-info ml-3">
                        <h6>Lynn Guini</h6>
                        <p class="badge bagde-pill badge-danger mb-0 font-size-12">Room 203</p>
                     </div>
                     <div class="iq-card-header-toolbar">
                        <div class="text-danger h6"><i class="ri-alert-fill"></i> Abnormal</div>
                     </div>
                  </div>
            </li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
         </ul> -->
      </div>
   </div>
</div>
@endsection

@section('script')
<script>
   var alert_num = 6;

   $(function(){
      $('#body').addClass(['sidebar-main-active', 'right-column-fixed', 'header-top-bgcolor']);
      var html = [
      '<li class="li-notif">'
      + '<div class="timeline-dots border-success"></div>'
      + '<h6 class="float-left mb-1">Lobby</h6>'
      + '<small class="float-right mt-1">9:35AM, Today</small>'
      + '<div class="d-flex w-100 mb-1 align-items-center">'
      + '<div class="user-img img-fluid"><img src="{{ asset("template/images/user/04.jpg") }}" alt="story-img" class="rounded-circle avatar-40"></div>'
      + '<div class="media-support-info ml-3">'
      + '<h6>Brock Lee</h6>'
      + '</div>'
      + '<div class="iq-card-header-toolbar">'
      + '<div class="text-success h6"><i class="ri-information-fill"></i> Entered</div>'
      + '</div>'
      + '</div>'
      + '</li>',
      '<li class="li-notif">'
      + '<div class="timeline-dots border-success"></div>'
      + '<h6 class="float-left mb-1">Lobby</h6>'
      + '<small class="float-right mt-1">9:35AM, Today</small>'
      + '<div class="d-flex w-100 mb-1 align-items-center">'
      + '<div class="user-img img-fluid"><img src="{{ asset("template/images/user/04.jpg") }}" alt="story-img" class="rounded-circle avatar-40"></div>'
      + '<div class="media-support-info ml-3">'
      + '<h6>Brock Lee</h6>'
      + '</div>'
      + '<div class="iq-card-header-toolbar">'
      + '<div class="text-success h6"><i class="ri-information-fill"></i> Left</div>'
      + '</div>'
      + '</div>'
      + '</li>',
      '<li class="li-notif">'
      + '<div class="timeline-dots border-success"></div>'
      + '<h6 class="float-left mb-1">Common Area</h6>'
      + '<small class="float-right mt-1">9:35AM, Today</small>'
      + '<div class="d-flex w-100 mb-1 align-items-center">'
      + '<div class="user-img img-fluid"><img src="{{ asset("template/images/user/02.jpg") }}" alt="story-img" class="rounded-circle avatar-40"></div>'
      + '<div class="media-support-info ml-3">'
      + '<h6>Brock Lee</h6>'
      + '</div>'
      + '<div class="iq-card-header-toolbar">'
      + '<div class="text-success h6"><i class="ri-information-fill"></i> Entered</div>'
      + '</div>'
      + '</div>'
      + '</li>',
      '<li class="li-alert active">'
      + '<div class="timeline-dots border-danger"></div>'
      + '<h6 class="float-left mb-1 text-danger">Violence 1</h6>'
      + '<small class="float-right mt-1">9:35AM, Today</small>'
      + '<div class="d-flex w-100 mb-1 align-items-center">'
      + '<div class="user-img img-fluid"><img src="{{ asset("template/images/user/02.jpg") }}" alt="story-img" class="rounded-circle avatar-40"></div>'
      + '<div class="media-support-info ml-3">'
      + '<h6>Brock Lee</h6>'
      + '<p class="badge bagde-pill badge-danger mb-0 font-size-12">Common Area 1</p>'
      + '</div>'
      + '<div class="iq-card-header-toolbar">'
      + '<div class="text-danger h6"><i class="ri-alert-fill"></i> Violence</div>'
      + '</div>'
      + '</div>'
      + '</li>',
      '<li class="li-alert active">'
      + '<div class="timeline-dots border-danger"></div>'
      + '<h6 class="float-left mb-1 text-danger">Inactivity 30 Minutes</h6>'
      + '<small class="float-right mt-1">9:35AM, Today</small>'
      + '<div class="d-flex w-100 mb-1 align-items-center">'
      + '<div class="user-img img-fluid"><img src="{{ asset("template/images/user/02.jpg") }}" alt="story-img" class="rounded-circle avatar-40"></div>'
      + '<div class="media-support-info ml-3">'
      + '<h6>Brock Lee</h6>'
      + '<p class="badge bagde-pill badge-danger mb-0 font-size-12">Toilet 1</p>'
      + '</div>'
      + '<div class="iq-card-header-toolbar">'
      + '<div class="text-danger h6"><i class="ri-alert-fill"></i> Abnormal</div>'
      + '</div>'
      + '</div>'
      + '</li>',
      '<li class="li-alert active">'
      + '<div class="timeline-dots border-danger"></div>'
      + '<h6 class="float-left mb-1 text-danger">Resident Dupress</h6>'
      + '<small class="float-right mt-1">9:35AM, Today</small>'
      + '<div class="d-flex w-100 mb-1 align-items-center">'
      + '<div class="user-img img-fluid"><img src="{{ asset("template/images/user/02.jpg") }}" alt="story-img" class="rounded-circle avatar-40"></div>'
      + '<div class="media-support-info ml-3">'
      + '<h6>Brock Lee</h6>'
      + '<p class="badge bagde-pill badge-danger mb-0 font-size-12">Room 301</p>'
      + '</div>'
      + '<div class="iq-card-header-toolbar">'
      + '<div class="text-danger h6"><i class="ri-alert-fill"></i> SOS</div>'
      + '</div>'
      + '</div>'
      + '</li>',
      '<li class="li-alert active">'
      + '<div class="timeline-dots border-danger"></div>'
      + '<h6 class="float-left mb-1 text-danger">Fall</h6>'
      + '<small class="float-right mt-1">9:35AM, Today</small>'
      + '<div class="d-flex w-100 mb-1 align-items-center">'
      + '<div class="user-img img-fluid"><img src="{{ asset("template/images/user/02.jpg") }}" alt="story-img" class="rounded-circle avatar-40"></div>'
      + '<div class="media-support-info ml-3">'
      + '<h6>Brock Lee</h6>'
      + '<p class="badge bagde-pill badge-danger mb-0 font-size-12">Toilet 1</p>'
      + '</div>'
      + '<div class="iq-card-header-toolbar">'
      + '<div class="text-danger h6"><i class="ri-alert-fill"></i> Fall</div>'
      + '</div>'
      + '</div>'
      + '</li>'];
      
      var prependActivity = function() {
         var i = Math.floor(Math.random() * 7);
         $(html[i]).hide().prependTo('#activity').fadeIn(1000);
         if ($('#activity li:first-child').hasClass('active')){
            if(alert_num == 0){
               $('#alert_num').removeClass('badge-secondary');
               $('#alert_num').addClass('badge-danger');

               $('#dropdownMenuButton1').removeClass('iq-bg-secondary');
               $('#dropdownMenuButton1').addClass('iq-bg-danger');
            }
            alert_num++;
            $('#alert_num').html(alert_num);
         }
         $('#activity li:last-child').remove();
      };

      // var timer = setInterval(prependActivity, 5000);
   });

   $(document).on('click','.li-alert', function(){
      if($(this).hasClass('active')){
         console.log('has class')
         $(this).find('div.timeline-dots').removeClass('border-danger');
         $(this).find('h6').removeClass('text-danger');
         $(this).find('div.d-flex p.badge').removeClass('badge-danger');
         $(this).find('div.d-flex div.h6').removeClass('text-danger');
         alert_num--;
         if(alert_num == 0){
            $('#alert_num').removeClass('badge-danger');
            $('#alert_num').addClass('badge-secondary');

            $('#dropdownMenuButton1').removeClass('iq-bg-danger');
            $('#dropdownMenuButton1').addClass('iq-bg-secondary');
         }
         $('#alert_num').html(alert_num);
         $(this).removeClass('active');
      }
   })
   
</script>

@endsection
