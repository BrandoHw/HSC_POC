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
   .apexcharts-series { cursor: pointer }
   .iq-card-icon { cursor: pointer }

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
                        <div class="rounded-circle iq-card-icon iq-bg-primary mr-3" href="{{ route('policies.index') }}" > <i class="ri-message-line"></i></div>
                        <div class="text-left">
                           <h4 id="icon-text-policy">{{ $policies_count }}</h4>
                           <p class="mb-0">Total Policies</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-lg-0">
                        <div class="rounded-circle iq-card-icon iq-bg-success mr-3" href="{{ route('gateways.index') }}" > <i class="ri-base-station-line"></i></div>
                        <div class="text-left">
                           <h4 id="icon-text-reader">{{ $readers_count }}</h4>
                           <p class="mb-0">Total Gateways</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="rounded-circle iq-card-icon iq-bg-info mr-3" href="{{ route('beacons.index') }}" > <i class="ri-share-line"></i></div>
                        <div class="text-left">
                           <h4 id="icon-text-tag">{{ $tags_count }}</h4>
                           <p class="mb-0">Total Beacons</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="rounded-circle iq-card-icon iq-bg-warning mr-3" href="{{ route('residents.index') }}" > <i class="ri-group-line"></i></div>
                        <div class="text-left">
                           <h4 id="icon-text-resident">{{ $residents_count }}</h4>
                           <p class="mb-0">Total Residents</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg">
                     <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="rounded-circle iq-card-icon iq-bg-danger mr-3" href="{{ route('alerts.index') }}" > <i class="ri-alarm-warning-line"></i></div>
                        <div class="text-left">
                           <h4 id="icon-text-alert">{{ $alerts_count }}</h4>
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
               <div class="text-center align-middle" style="margin-top: 5rem; margin-bottom: 8rem" id="no-attendance-div" {{ (count($attendance_policies) < 1) ? "":"hidden"}}>
                  <div data-icon="*" style="font-size: 55px" class="icon text-secondary" ></div>
                  <p>No Attendance Policy Found!</p>
               </div>
               <div id="attendance-table" {{ (count($attendance_policies) < 1) ? "hidden":""}}>
                  <ul class="nav nav-tabs justify-content-right" id="attendance-nav" role="tablist">
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
                                 <div class="iq-card-body" style="padding: 15px">
                                    <div class="table-responsive" style="overflow-x: hidden">
                                       <table class="table" id="table-{{ $policy->rules_id }}">
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
      <div class="col-lg-4">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h4 class="card-title">Attendance Chart</h4>
               </div>
               <div class="iq-card-header-toolbar d-flex align-items-center">
                  <button type="button" class="btn custom-btn iq-bg-primary" id="refresh-attendance" onClick="reloadTableData()" {{ (count($attendance_policies) < 1) ? "hidden":""}}><i class="ri-refresh-line mr-0"></i></button>
               </div>
            </div>
            <div class="iq-card-body">
               <div class="text-center align-middle" style="margin-top: 5rem; margin-bottom: 8rem" id="no-attendance-chart-div" {{ (count($attendance_policies) < 1) ? "":"hidden"}}>
                  <div data-icon="&#xe002" style="font-size: 55px" class="icon text-secondary" ></div>
                  <p>No Attendance Policy Found!</p>
               </div>
               <div id="home-perfomer-chart" {{ (count($attendance_policies) < 1) ? "hidden":""}}></div>
               <form method="get" name="form" id="attendance-form" action="{{ route('attendance.index') }}">
                  <input type="text" name="data" id="attendance-data" value='0' hidden>
               </form>
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
         <div class="text-center align-middle" style="margin-top: 12rem" id="no-alert-div" {{ ($alerts_count < 1) ? "":"hidden"}}>
            <div data-icon="x" style="font-size: 85px" class="icon text-secondary" ></div>
            <p>No Alert Right Now!</p>
         </div>
         <ul class=" list-inline p-0 m-0" id="alert-all" {{ (count($alerts) >= 1) ? "":"hidden"}}>
            <div class="text-center align-middle" style="margin-top: 12rem" id="loading-alert-div">
               <div data-icon="&#xe011" style="font-size: 85px" class="icon text-primary" ></div>
               <p>Loading Alerts!</p>
            </div>
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

      getNewAlerts(true);
      let timer_icon = setInterval(reloadIconData, 30000);
      let timer_tables = setInterval(reloadTableData, 30000);
      let timer_alerts = setInterval(getNewAlerts, 30000);
      let timer_alerts_diff = setInterval(updateTimeDiff, 60000);

   });

   @foreach($attendance_policies as $policy)
   /* Initiate dataTable */
   let table_{{ $policy->rules_id }} = $('#table-{{ $policy->rules_id }}').DataTable({
   dom:'rt',  
   processing: true,
      serverSide: false,
      ajax: {
         url: '{{ route("attendance.date") }}',
         data: function(data) {
               data.rule_id = {{ $policy->rules_id }};
               data.date = -1;
               data.num = 5;
         }
      },
      columns:[
         {data: 'name', checkboxes: false, orderable: true},
         {data: 'type'},
         {data: 'attendance'},
         {data: 'curr_loc'},
         {data: 'detected_at'},
      ],
      order: [[4, 'asc']],
   });
   @endforeach

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

   $('.iq-card-icon').on('click', function(){
      window.location.href = $(this).attr('href');
   })

   function reloadIconData(){
      let result = {
         _token: $('meta[name="csrf-token"]').attr('content')
      };

      $.ajax({
         url: '{{ route("home.icon") }}',
         type: "GET",
         data: result,
         success:function(response){
            let errors = response['errors'];
            if($.isEmptyObject(response['success'])){
               console.log(errors);
            } else {
               $('#icon-text-policy').html(response['policy']);
               $('#icon-text-reader').html(response['reader']);
               $('#icon-text-tag').html(response['tag']);
               $('#icon-text-resident').html(response['resident']);
               $('#icon-text-alert').html(response['alert']);
            }
         },
         error:function(error){
            console.log(error);
         }
      });
   }

   function reloadTableData(){
      if($('#attendance-table').is(":visible")){
         let refresh_btn = $('#refresh-attendance');
         refresh_btn.html('<i class="fa fa-custom fa-circle-o-notch fa-spin mr-0"></i>');
         refresh_btn.addClass('custom-disabled');
   
         reloadChartData();
         @foreach($attendance_policies as $policy)
            table_{{ $policy->rules_id }}.ajax.reload();
         @endforeach
   
         setTimeout(function() {
            refresh_btn.html('<i class="fa fa-custom fa-check mr-0"></i>');
            // notyf.success('Attendance updated successfully');
         }, 500);
         setTimeout(function() {
            refresh_btn.html('<i class="ri-refresh-line mr-0"></i>');
            refresh_btn.removeClass('custom-disabled');
         }, 1000);
      }
   }
   
   $(document).on('click','.li-alert', function(){
      if($(this).hasClass('active')){
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

   function getNewAlerts(first = false){
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
                  if(!$('#loading-alert-div').is(":hidden")){
                     $('#loading-alert-div').prop('hidden', true);
                  }
               }

               let alerts_grouped = response['alerts_grouped'];
               last = response['last_id'];
               Object.keys(alerts_grouped).forEach(function(key){
                  let tag_id = key;
                  let alerts_rule = alerts_grouped[key][0];
                  let target_loc = alerts_grouped[key][1];
                  let all_num = alerts_grouped[key][2];

                  if(alerts_rule.length > 0){
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
                        

                        Object.values(alerts_rule).forEach(setNewAlerts);
                        
                     } else {

                        let alert_main = $('#main-group-' + tag_id);
                        
                        if(alerts_rule.length > 0){

                           let alerts_badge = alert_main.find('.media-support-amount .all-alert-num');
                           let resolve_all_btn = $('#resolve-by-tag-' + tag_id);

                           alerts_badge.html(parseInt(alerts_badge.text()) + all_num);
                           alerts_badge.prop('hidden', false);
                           resolve_all_btn.prop('hidden', false);

                           Object.values(alerts_rule).forEach(setNewAlerts);
                        }

                        alert_main.prependTo($('#alert-all'));

                     }

                     if(all_num < 1 ){
                        $('#resolve-by-tag-' + tag_id).hide();
                        let alerts_badge = $('#main-group-' + tag_id).find('.media-support-amount .all-alert-num');
                        alerts_badge.prop('hidden', true);
                     }
                  }

                  $('#main-group-' + tag_id).find('.media-support-info .tag_loc').html(target_loc);

               })

               if(response['alerts_num'] != 0){
                  if(!first){
                     notyf.open({
                        type: 'warning',
                        message: response['success'],
                     });
                  }
               }
               
               refresh_btn.html('<i class="fa fa-custom fa-check mr-0"></i>');
               setTimeout(function() {
                  refresh_btn.html('<i class="ri-refresh-line mr-0"></i>');
                  refresh_btn.prop('disabled', false);
               }, 1000);
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

      if(num < 1){
         let alert_div = $('#tag-' + tag_id + "-rule-" + rule_id);
         let alert_badge = alert_div.find('.media-support-amount .alert-num');
         let alert_span = alert_div.find('.media-support-amount span');
         let resolve_btn = alert_div.find('.alert-resolve');
         alert_badge.prop('hidden', true);
         alert_badge.html('0');
         alert_span.prop('hidden', false);
         resolve_btn.prop('hidden', true);
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
         // series: [1, 10, 20, 30, 40, 50, 60],
         series: @json($attendance),
         chart: {
            id: 'home-perfomer-chart',
            height: 350,
            type: 'radialBar',
            events: {
               dataPointSelection: function(event, chartContext, config) {
                  let label = config.w.config.labels[config.dataPointIndex];
                  $('#attendance-data').val(label);
                  $('#attendance-form').submit();
               }, 
            }
         },
         colors: @json($colors),
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
                     label: 'Overall',
                     formatter: function (w) {
                        let series = w.config.series;
                        let avg = 0;
                        series.forEach(function(item){
                           avg += item;
                        }, avg);
                        avg /= series.length;
                        return avg.toFixed(2) + "%";
                     }
                  }
               }
            }
         },
         labels: @json($attendance_policies->pluck('description')->all()),
         
      };

      var chart = new ApexCharts(document.querySelector("#home-perfomer-chart"), options);
      chart.render();
    }

    function reloadChartData(){
      let result = {
         _token: $('meta[name="csrf-token"]').attr('content')
      };

      $.ajax({
         url: '{{ route("attendance.chart") }}',
         type: "GET",
         data: result,
         success:function(response){
            let errors = response['errors'];
            if($.isEmptyObject(response['success'])){
               console.log(errors);
            } else {
               if(response["labels_data"].length > 0){
                  /* Hide deleted attendance policy */
                  let attendances_id = @json($attendance_policies->pluck('rules_id')->all());
                  let not_exist = $.grep(attendances_id, function(i){return $.inArray(i, response['exist']) == -1});

                  if(not_exist.length > 0){
                        not_exist.forEach(function(item){
                           $('#attendance-' + item).prop("hidden", true);
                           $('#tab-' + item).prop("hidden", true);
                        })
                  }

                  /* Update attendance chart */
                  ApexCharts.exec('home-perfomer-chart', 'updateOptions', {
                     labels: response["labels_data"],
                     series: response["series_data"]
                  }, false, true);

               } else {
                  $('#home-perfomer-chart').prop('hidden', true);
                  $('#attendance-table').prop('hidden', true);
                  $('#refresh-attendance').prop('hidden', true);

                  $('#no-attendance-chart-div').prop('hidden', false);
                  $('#no-attendance-div').prop('hidden', false);
               }
            }
         },
         error:function(error){
            console.log(error);
         }
      });
    }
</script>

@endsection
