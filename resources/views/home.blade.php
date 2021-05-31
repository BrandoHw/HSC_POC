@extends('layouts.app')

@section('style')
<style>
   .alert-resolve { margin: 0; padding: 0; float: right; 
      position: absolute; line-height: 62px; right: -200px; top: 0; background: var(--iq-white); font-size: 18px; padding: 0 30px; transition: all 0.3s ease-in-out; transition: all 0.3s ease-in-out; -moz-transition: all 0.3s ease-in-out; -ms-transition: all 0.3s ease-in-out; -o-transition: all 0.3s ease-in-out; -webkit-transition: all 0.3s ease-in-out;
   }
   .alert-resolve li { list-style: none; float: left; margin-right: 10px; }
   .alert-resolve li:last-child { margin-right: 0; }
   .alert-resolve li a { height: 30px; width: 30px; text-align: center; font-size: 18px; line-height: 30px; display: inline-block; -webkit-border-radius: 7px; -moz-border-radius: 7px; border-radius: 7px; background: rgba(130, 122, 243, 0.2); background: -moz-linear-gradient(left, rgba(130, 122, 243, 0.2) 0%, rgba(180, 122, 243, 0.2) 100%); background: -webkit-gradient(left top, right top, color-stop(0%, rgba(130, 122, 243, 0.2)), color-stop(100%, rgba(180, 122, 243, 0.2))); background: -webkit-linear-gradient(left, rgba(130, 122, 243, 0.2) 0%, rgba(180, 122, 243, 0.2) 100%); background: -o-linear-gradient(left, rgba(130, 122, 243, 0.2) 0%, rgba(180, 122, 243, 0.2) 100%); background: -ms-linear-gradient(left, rgba(130, 122, 243, 0.2) 0%, rgba(180, 122, 243, 0.2) 100%); background: linear-gradient(to right, rgba(130, 122, 243, 0.2) 0%, rgba(180, 122, 243, 0.2) 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='var(--iq-primary)', endColorstr='var(--iq-primary-light)', GradientType=1); color: var(--iq-primary) !important; }
   .alert-resolve li a:hover { text-decoration: none; }
   .alert-ul li:hover .alert-resolve { right: 0; }
   .alert-main:hover .alert-resolve { right: 0; }
   .ri-refresh-line { font-size: 20px; }
   .fa-custom { font-size: 17px; margin-top: 5px}
   .custom-btn { height: 40px; width: 40px; padding: 0; border-radius: 10px; }

</style>
@endsection
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
                           <h4>{{ $policies_count }}</h4>
                           <p class="mb-0">Total Policies</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-lg-0">
                        <div class="rounded-circle iq-card-icon iq-bg-success mr-3"> <i class="ri-base-station-line"></i></div>
                        <div class="text-left">
                           <h4>{{ $readers_count }}</h4>
                           <p class="mb-0">Total Gateways</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="rounded-circle iq-card-icon iq-bg-info mr-3"> <i class="ri-share-line"></i></div>
                        <div class="text-left">
                           <h4>{{ $tags_count }}</h4>
                           <p class="mb-0">Total Beacons</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="rounded-circle iq-card-icon iq-bg-warning mr-3"> <i class="ri-group-line"></i></div>
                        <div class="text-left">
                           <h4>{{ $residents_count }}</h4>
                           <p class="mb-0">Total Residents</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="rounded-circle iq-card-icon iq-bg-danger mr-3"> <i class="ri-alarm-warning-line"></i></div>
                        <div class="text-left">
                           <h4>{{ $alerts_count }}</h4>
                           <p class="mb-0">Total Alerts</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      @can('attendance-list')
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
      @endcan
      @can('tracking-list')
      <div class="col-lg-12">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-body">
               @include ("map.dashboard")
            </div>
         </div>
      </div>
      @endcan
      
   </div>
</div>


@endsection

@section('extra')
@can('alert-list')
<div class="iq-right-fixed">
   <div class="iq-card" style="box-shadow: none;">
      <div class="iq-card-header d-flex justify-content-between">
         <div class="iq-header-title">
            <h4 class="card-title">Today's Alert</h4>
         </div>
         <div class="iq-card-header-toolbar d-flex align-items-center">
            <button type="button" class="btn custom-btn iq-bg-primary" id="refresh-alerts" onClick="getNewAlerts()"><i class="ri-refresh-line mr-0"></i></button>
         </div>
      </div>
      <div class="chat-sidebar-channel scroller pl-3 pr-3" style="height:calc(100vh - 5rem)">
         <div class="text-center align-middle" style="margin-top: 12rem" id="no-alert-div" {{ (count($alerts) < 1) ? "":"hidden"}}>
            <div data-icon="x" style="font-size: 85px" class="icon text-secondary" ></div>
            <p>No Alert Right Now!</p>
         </div>
         <ul class=" list-inline p-0 m-0" id="alert-all" {{ (count($alerts) >= 1) ? "":"hidden"}}>
            @if(count($alerts) >= 1)
               @foreach($alerts->groupBy('beacon_id') as $alerts_person)
                  @php($person = $alerts_person->first()->tag->resident ? $alerts_person->first()->tag->resident: $alerts_person->first()->tag->user)
                  <li class="mb-3 sell-list border-info rounded" id="main-group-{{ $person->tag->beacon_id }}">
                     <div class="d-flex p-3 align-items-center alert-main">
                        <div class="user-img img-fluid">
                           <img src="{{ asset('img/avatars/default-profile-m.jpg') }}" alt="story-img" class="img-fluid rounded-circle avatar-40">
                        </div>
                        <div class="media-support-info ml-3">
                           <h6>{{ $person->full_name}}</h6>
                           <p class="mb-0 tag_loc">{{ $person->tag->current_location }}</p>
                        </div>
                        <div class="media-support-amount ml-3">
                           <div class="text-secondary small">{{ $alerts_person->first()->occured_at_time_tz }}</div>
                           <div class="badge badge-pill badge-danger all-alert-num" style="float: right" {{ $alerts_person->whereNull('resolved_at')->count() > 0 ? '':'hidden' }}>{{ $alerts_person->whereNull('resolved_at')->count() }}</div>
                        </div>
                     </div>
                     <button id="resolve-by-tag-{{ $person->tag->beacon_id }}" onClick="resolveAllAlerts(this.id)" class="btn btn-outline-primary mb-2" style="margin-left: 4.25rem; margin-top:-0.5rem"
                        {{ $alerts_person->whereNull('resolved_at')->count() > 0 ? '':'hidden' }}>Resolve All</button>
                     <ul class="list-group alert-ul" id="group-by-tag-{{ $person->tag->beacon_id }}">
                        @foreach($alerts_person->groupBy('rules_id') as $alerts_grouped)
                           @switch($alerts_grouped->first()->policy->rules_type_id)
                           @case(1)
                              @php($color = $alerts_grouped->first()->policy->attendance ? "success":"warning")
                              @php($desc = $alerts_grouped->first()->policy->attendance ? "Present":"Absent")
                              @break
                           @case(2)
                              @php($color = "warning")
                              @php($desc = "<".$alerts_grouped->first()->policy->battery_threshold."%")
                              @break
                           @case(3)
                              @php($color = "danger")
                              @php($desc = "SOS")
                              @break
                           @case(4)
                              @php($color = "danger")
                              @php($desc = "Fall")
                              @break
                           @case(5)
                              @php($color = "warning")
                              @php($desc = $alerts_grouped->first()->policy->geofence ? "Entered":"Left")
                              @break
                           @case(6)
                              @php($color = "danger")
                              @php($desc = "Violence")
                              @break
                           @endswitch
                           @php($id = $alerts_grouped->first()->beacon_id)
                           @php($rule = $alerts_grouped->first()->rules_id)
                           <li class="list-group-item d-flex align-items-center" id="tag-{{ $id }}-rule-{{ $rule }}">
                              <div class="media-support-info">
                                 <h6 class="rule-name">{{ $alerts_grouped->first()->policy->description }}</h6>
                                 <p class="curr-loc">{{ $alerts_grouped->first()->reader->location_full }}</p>
                              </div>
                              <div class="media-support-amount ml-3">
                                 <div class="text-secondary small time-diff"><em>{{ $alerts_grouped->first()->time_diff_tz }}</em></div>
                                 <div class="badge badge-pill badge-{{ $color }} alert-num" style="float: right" {{ $alerts_grouped->whereNull('resolved_at')->count() > 0 ? '':'hidden'}}>{{ $alerts_grouped->whereNull('resolved_at')->count() }}</div>
                                 <span class="text-success" {{ $alerts_grouped->whereNull('resolved_at')->count() > 0 ? 'hidden':''}}><i class="ri-check-line"></i>Resolved</span>
                              </div>
                              <ul class="alert-resolve" {{ $alerts_grouped->whereNull('resolved_at')->count() > 0 ? '':'hidden'}}>
                                 <li id="resolve-by-tag-{{ $id }}-rule-{{ $rule }}" onClick="resolveAlerts(this.id)"><a href="#"><i class="ri-checkbox-line"></i></a></li>
                              </ul>
                           </li>
                           @endforeach
                     </ul>
                  </li>
               @endforeach
            @endif
         </ul>
      </div>
   </div>
</div>
@endcan
@endsection

@section('script')
<script>
   let last = @json($alerts_last);
   
   $(function(){
      $('#body').addClass(['sidebar-main-active', 'right-column-fixed', 'header-top-bgcolor']);

      let timer = setInterval(getNewAlerts, 30000);
      let timer_diff = setInterval(updateTimeDiff, 60000);
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

   function updateTimeDiff(){
      if($('.time-diff em').length){
         $('.time-diff em').each(function(){
            let string = $(this).html().split(' ');
            let minute = -1;
            let hour = -1;

            if(string.length >= 3){
               hour = parseInt(string[0].split('h')[0]);
               minute = parseInt(string[1].split('m')[0]);
            } else {
               minute = parseInt(string[0].split('m')[0]);
            }
            minute += 1;
      
            if(minute >= 60){
               if(hour == -1){
                  hour = 1;
               } else {
                  hour += 1;
               }
               minute = 0;
            }
      
            let message = minute + "ms ago";
      
            if(hour > 0){
               message = hour + "hrs " + message;
            }
            $(this).html(message);
         })
      }
   }

   function getNewAlerts(){
      let refresh_btn = $('#refresh-alerts');
      refresh_btn.html('<i class="fa fa-custom fa-circle-o-notch fa-spin mr-0"></i>');
      refresh_btn.prop('disabled', true);

      let result = {
         last_id: last,
         _token: $('meta[name="csrf-token"]').attr('content')
      };

      $.ajax({
         url: '{{ route("alerts.new") }}',
         type: "POST",
         data: result,
         success:function(response){
            let errors = response['errors'];
            if($.isEmptyObject(response['success'])){
               console.log(errors);
            } else {
               if(response['alerts_num'] != 0){
                  /* Check whether there is existing alerts */
                  if($("#alert-all").is(":hidden")){
                     $('#no-alert-div').prop('hidden', true);
                     $('#alert-all').prop('hidden', false);
                  }

                  let alerts_grouped = response['alerts_grouped'];
                  last = response['last_id'];
                  Object.keys(alerts_grouped).forEach(function(key){
                     let tag_id = key;
                     let alerts_rule = alerts_grouped[key][0];
                     let target_loc = alerts_grouped[key][1];
                     let all_num = alerts_grouped[key][2];

                     if(!$('#main-group-' + tag_id).length){
                        let occured_at = alerts_rule[0]['occured_at_time_tz'];
                        let person = alerts_rule[0]['tag']['user'] ?? alerts_rule[0]['tag']['resident']; 
                        let f_name = person['fName'] ?? person['resident_fName'];
                        let l_name = person['lName'] ?? person['resident_lName'];
                        let full_name = f_name + ' ' + l_name;

                        let new_alert_main = '<li class="mb-3 sell-list border-info rounded" id="main-group-'+ tag_id  +'">'
                        + '<div class="d-flex p-3 align-items-center alert-main">'
                           + '<div class="user-img img-fluid">'
                              + '<img src="{{ asset("img/avatars/default-profile-m.jpg") }}" alt="story-img" class="img-fluid rounded-circle avatar-40">'
                           + '</div>'
                           + '<div class="media-support-info ml-3">'
                              + '<h6>'+ full_name +'</h6>'
                              + '<p class="mb-0 tag_loc">'+ target_loc +'</p>'
                           + '</div>'
                           + '<div class="media-support-amount ml-3">'
                              + '<div class="text-secondary small">'+ occured_at +'</div>'
                              + '<div class="badge badge-pill badge-danger all-alert-num" style="float: right">'+ all_num +'</div>'
                           + '</div>'
                        + '</div>'
                        + '<button id="resolve-by-tag-'+ tag_id +'" onClick="resolveAllAlerts(this.id)" class="btn btn-outline-primary mb-2" style="margin-left: 4.25rem; margin-top:-0.5rem">Resolve All</button>'
                        + '<ul class="list-group alert-ul" id="group-by-tag-'+ tag_id +'"></ul>';
                        + '</li>';

                        $('#alert-all').prepend(new_alert_main);

                        alerts_rule.forEach(setNewAlerts);
                        
                     } else {

                        let alert_main = $('#main-group-' + tag_id);
                        alert_main.find('.media-support-info .tag_loc').html(target_loc);
                        
                        if(alerts_rule.length > 0){

                           let alerts_badge = alert_main.find('.media-support-amount .all-alert-num');
                           let resolve_all_btn = $('#resolve-by-tag-' + tag_id);

                           alerts_badge.html(parseInt(alerts_badge.text()) + all_num);
                           alerts_badge.prop('hidden', false);
                           resolve_all_btn.prop('hidden', false);

                           alerts_rule.forEach(setNewAlerts);
                        }

                        alert_main.prependTo($('#alert-all'));

                     }
                  })
                  notyf.open({
                     type: 'warning',
                     message: response['success'],
                  });
               }
               refresh_btn.html('<i class="ri-refresh-line mr-0"></i>');
               refresh_btn.prop('disabled', false);
            }
         },
         error:function(error){
            console.log(error);
         }
      });
   }

   function setNewAlerts(item){
      let rule_id = item['rules_id'];
      let tag_id = item['beacon_id'];
      let num = item['counts'];
      let policy = item['policy']['description'];
      let time_diff = item['time_diff_tz'];
      let curr_loc = item['reader']['location']['location_description'];
      let color = 'warning';
      switch(item['policy']['rules_type_id']){
         case "1":
            color = item['policy']['attendance'] ? 'success':'warning';
            break
         case "2":
            color = 'warning';
            break
         case "3":
            color = 'danger';
            break
         case "4":
            color = 'danger';
            break
         case "5":
            color = 'warning';
            break
         case "6":
            color = 'danger';
            break
      }
      
      if(!$('#tag-' + tag_id + "-rule-" + rule_id).length){
         let new_alert_div = '<li class="list-group-item d-flex align-items-center" id="tag-'+ tag_id +'-rule-'+ rule_id +'">'
            + '<div class="media-support-info">'
               + '<h6 class="rule-name">'+ policy +'</h6>'
               + '<p class="curr-loc">'+ curr_loc +'</p>'
            + '</div>'
            + '<div class="media-support-amount ml-3">'
               + '<div class="text-secondary small time-diff"><em>'+ time_diff +'</em></div>'
               + '<div class="badge badge-pill badge-'+ color +' alert-num" style="float: right">'+ num +'</div>'
               + '<span class="text-success" hidden ><i class="ri-check-line"></i>Resolved</span>'
            + '</div>'
            + '<ul class="alert-resolve">'
               + '<li id="resolve-by-tag-'+ tag_id +'-rule-'+ rule_id +'" onClick="resolveAlerts(this.id)"><a href="#"><i class="ri-checkbox-line"></i></a></li>'
            + '</ul>'
         + '</li>';

         $('#group-by-tag-' + tag_id).append(new_alert_div);

      } else {
         let alert_div = $('#tag-' + tag_id + "-rule-" + rule_id);
         let curr_div = alert_div.find('.media-support-info .curr-loc');
         let alert_badge = alert_div.find('.media-support-amount .alert-num');
         let time_div = alert_div.find('.media-support-amount .time-diff');
         let resolve_btn = alert_div.find('.alert-resolve');

         curr_div.html(curr_loc); 
         alert_badge.html(parseInt(alert_badge.text()) + num);
         alert_badge.prop('hidden', false);
         time_div.html('<em>' + time_diff + '</em>');
         resolve_btn.prop('hidden', false);

         /* Make sure hide Resolved text */
         alert_div.find('.media-support-amount span').prop('hidden', true);

         alert_div.prependTo($('#group-by-tag-' + tag_id));

      }
   }

   function resolveAllAlerts(id){
      let resolve_all_btn = $('#' + id);
      let tag_id = id.split('-')[3];

      resolve_all_btn.html('<i class="fa fa-circle-o-notch fa-spin"></i>Resolving');

      let alerts_badge = $('#main-group-' + tag_id).find('.media-support-amount .all-alert-num');
      let alerts_list = $('#main-group-' + tag_id).find('.alert-ul .list-group-item');
      let resolve_btn = $('#main-group-' + tag_id).find('.alert-ul .list-group-item .alert-resolve');
      
      let result = {
         tag_id: tag_id,
         user_id: @json(auth()->user()->user_id),
         _token: $('meta[name="csrf-token"]').attr('content')
      };

      $.ajax({
         url: '{{ route("alerts.resolve_all") }}',
         type: "PATCH",
         data: result,
         success:function(response){
            let errors = response['errors'];
            if($.isEmptyObject(response['success'])){
               console.log(errors);
            } else {
               alerts_list.each(function(){
                  $(this).find('.media-support-amount .alert-num').html('0');
                  $(this).find('.media-support-amount .alert-num').prop('hidden', true);
                  $(this).find('.media-support-amount span').prop('hidden', false);
               })

               alerts_badge.prop('hidden', true);
               alerts_badge.html('0');
               resolve_all_btn.html('<i class="fa fa-check"></i>Resolved');
               resolve_btn.hide();
               
               setTimeout(function() {
                  resolve_all_btn.hide();
                  resolve_all_btn.html('Resolve All');
               }, 1000);
               notyf.success(response['success']);
            }
         },
         error:function(error){
               console.log(error);
         }
      });
   }

   function resolveAlerts(id){
      let resolve_btn = $('#' + id);
      let tag_id = id.split('-')[3];
      let rule_id = id.split('-')[5];

      resolve_btn.html('<a href="#" disabled><i class="fa fa-circle-o-notch fa-spin"></i></a>');

      let alert_div = $('#tag-' + tag_id + "-rule-" + rule_id);
      let alerts_badge = $('#main-group-' + tag_id).find('.media-support-amount .all-alert-num');
      let alert_badge = alert_div.find('.media-support-amount .alert-num');
      let alert_span = alert_div.find('.media-support-amount span');
      let num = parseInt(alerts_badge.text()) - parseInt(alert_badge.text());

      let result = {
         tag_id: tag_id,
         rule_id: rule_id,
         user_id: @json(auth()->user()->user_id),
         _token: $('meta[name="csrf-token"]').attr('content')
      };

      $.ajax({
         url: '{{ route("alerts.resolve") }}',
         type: "PATCH",
         data: result,
         success:function(response){
            let errors = response['errors'];
            if($.isEmptyObject(response['success'])){
               console.log(errors);
            } else {
               alert_badge.prop('hidden', true);
               alert_badge.html('0');
               resolve_btn.html('<a href="#" disabled><i class="fa fa-check"></i></a>');
               alert_span.prop('hidden', false);
               alerts_badge.html(num);

               if(num < 1){
                  $('#resolve-by-tag-' + tag_id).hide();
                  alerts_badge.prop('hidden', true);
               }
               
               setTimeout(function() {
                  resolve_btn.hide();
                  resolve_btn.html('<a href="#"><i class="ri-checkbox-line"></i></a>');
               }, 1000);
               notyf.success(response['success']);
            }
         },
         error:function(error){
               console.log(error);
         }
      });
   }

   if($('#home-perfomer-chart').length){
      let options = {
         series: @json($attendance),
         chart: {
            height: 350,
            type: 'radialBar',
         },
         colors: ['#827af3','#e64141','#ffd400','#00d0ff', '#ffd400','#00d0ff','#00d0ff'],
         plotOptions: {
            radialBar: {
               dataLabels: {
               name: {
                  fontSize: '22px',
               },
               value: {
                  fontSize: '16px',
               },
               total: {
                  show: true,
                  label: 'Total',
                  formatter: function (w) {
                     // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                     return 249
                  }
               }
               }
            }
         },
         labels: @json($attendance_policies->pluck('description')->all()),
         events: {
            click: function(event, chartContext, config){
               console.log("detect");
               // window.location.href = '{{ route("attendance.index") }}';
            }
         }
      };

      var chart = new ApexCharts(document.querySelector("#home-perfomer-chart"), options);
      chart.render();
    }

    if(jQuery('#progress-chart-3').length){
    	
		
		var options = {
		  chart: {
			type: 'radialBar',
			//width:320,
			height: 300,
			offsetY: 0,
			offsetX: 0,
			
		  },
		  plotOptions: {
			radialBar: {
			  size: undefined,
			  inverseOrder: false,
			  hollow: {
				margin: 0,
				size: '20%',
				background: 'transparent',
			  },
			  
			  
			  
			  track: {
				show: true,
				background: '#e1e5ff',
				strokeWidth: '15%',
				opacity: 1,
				margin: 15, // margin is in pixels
			  },


			},
		  },
		  responsive: [{
          breakpoint: 480,
          options: {
			chart: {
			offsetY: 0,
			offsetX: 0
		  },	
            legend: {
              position: 'bottom',
              offsetX:0,
              offsetY: 0
            }
          }
        }],
		
		fill: {
          opacity: 1
        },
		
		colors:['#827af3', '#27b345', '#6ce6f4'],
		series: [75, 70, 72],
		labels: ['Total', 'Panding', 'Success'],
		legend: {
			fontSize: '16px',  
			show: false,
		  },		 
		}

        var chart = new ApexCharts(document.querySelector("#progress-chart-3"), options);
        chart.render();
    }
   
</script>

@endsection
