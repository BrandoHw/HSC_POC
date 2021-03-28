@extends('layouts.app')

@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-lg-4">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
               <div class="iq-card-header d-flex justify-content-between">
                  <div class="iq-header-title">
                     <h4 class="card-title">Events</h4>
                  </div>
                  <div class="iq-card-header-toolbar d-flex align-items-center">
                     <div class="dropdown">
                           <span class="dropdown-toggle btn mb-1 iq-bg-danger" id="dropdownMenuButton1" data-toggle="dropdown">
                              Unresolved
                              <span class="badge badge-danger ml-2" id="alert_num">6</span>
                           </span>
                           <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" style="">
                              <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                              <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                              <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                              <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                              <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                           </div>
                     </div>
                  </div>
               </div>
               <div class="iq-card-body">
                  <ul class="iq-timeline" id="activity">
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
                  </ul>
               </div>
         </div>
      </div>
      <div class="col-lg-8">
         <div class="row">
            <div class="col-lg-6">
               <div class="iq-card iq-card-block iq-card-stretch">
                  <div class="iq-card-body">
                     <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="ml-2">
                           <h5 class="mb-1">Total Alerts</h5>
                           <span class="h4 text-dark mb-0 counter d-inline-block w-100">68,586</span>
                        </div>
                        <div class="icon iq-icon-box bg-primary rounded-circle m-0" data-wow-delay="0.2s">
                           <i class="ri-user-fill"></i>
                        </div>
                     </div>
                  </div>
                  <div id="service-chart-01"></div>
               </div>
            </div>
            <div class="col-lg-6">
               <div class="iq-card iq-card-block iq-card-stretch">
                  <div class="iq-card-body">
                     <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="ml-2">
                           <h5 class="mb-1">Total Rules</h5>
                           <span class="h4 text-dark mb-0 counter d-inline-block w-100">35,859</span>
                        </div>
                        <div class="icon iq-icon-box bg-danger rounded-circle m-0" data-wow-delay="0.2s">
                           <i class="ri-user-fill"></i>
                        </div>
                     </div>
                  </div>
                  <div id="service-chart-03"></div>
               </div>
            </div>
            <div class="col-lg-6">
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
            <div class="col-lg-6">
               <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                  <div class="iq-card-header d-flex justify-content-between">
                     <div class="iq-header-title">
                        <h4 class="card-title">Absent</h4>
                     </div>
                     <div class="iq-card-header-toolbar d-flex align-items-center">
                        <div class="dropdown">
                              <span class="dropdown-toggle btn mb-1 iq-bg-info text-primary" id="dropdownMenuButton2" data-toggle="dropdown">
                                 Lunch
                                 <span class="badge badge-info ml-2">15</span>
                              </span>
                              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton2" style="">
                                 <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                 <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                 <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                 <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                 <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                              </div>
                        </div>
                     </div>
                  </div>
                  <div class="iq-card-body">
                     <ul class="perfomer-lists m-0 p-0">
                        <li class="d-flex mb-4 align-items-center">
                           <div class="user-img img-fluid"><img src="{{ asset('template/images/user/01.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                           <div class="media-support-info ml-3">
                              <h6>Paul Molive</h6>
                              <p class="badge bagde-pill badge-primary mb-0 font-size-12">Common Area 1</p>
                           </div>
                           <div class="iq-card-header-toolbar d-flex align-items-center">
                              <div class="dropdown">
                                 <span class="dropdown-toggle text-primary" id="dropdownMenuButton41" data-toggle="dropdown" aria-expanded="false" role="button">
                                    <i class="ri-more-2-line"></i>
                                 </span>
                                 <div class="dropdown-menu dropdown-menu-right" style="">
                                    <a class="dropdown-item" href="#"><i class="ri-eye-line mr-2"></i>View</a>
                                    <a class="dropdown-item" href="#"><i class="ri-bookmark-line mr-2"></i>Appointment</a>
                                 </div>
                              </div>
                           </div>
                        </li> 
                        <li class="d-flex mb-4 align-items-center">
                           <div class="user-img img-fluid"><img src="{{ asset('template/images/user/02.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                           <div class="media-support-info ml-3">
                              <h6>Barb Dwyer</h6>
                              <p class="badge bagde-pill badge-primary mb-0 font-size-12">Common Area 1</p>
                           </div>
                           <div class="iq-card-header-toolbar d-flex align-items-center">
                              <div class="dropdown show">
                                 <span class="dropdown-toggle text-primary" id="dropdownMenuButton42" data-toggle="dropdown" aria-expanded="true" role="button">
                                    <i class="ri-more-2-line"></i>
                                 </span>
                                 <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#"><i class="ri-eye-line mr-2"></i>View</a>
                                    <a class="dropdown-item" href="#"><i class="ri-bookmark-line mr-2"></i>Appointment</a>
                                 </div>
                              </div>
                           </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                           <div class="user-img img-fluid"><img src="{{ asset('template/images/user/03.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                           <div class="media-support-info ml-3">
                              <h6>Terry Aki</h6>
                              <p class="badge bagde-pill badge-primary mb-0 font-size-12">Common Area 1</p>
                           </div>
                           <div class="iq-card-header-toolbar d-flex align-items-center">
                              <div class="dropdown show">
                                 <span class="dropdown-toggle text-primary" id="dropdownMenuButton43" data-toggle="dropdown" aria-expanded="true" role="button">
                                    <i class="ri-more-2-line"></i>
                                 </span>
                                 <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#"><i class="ri-eye-line mr-2"></i>View</a>
                                    <a class="dropdown-item" href="#"><i class="ri-bookmark-line mr-2"></i>Appointment</a>
                                 </div>
                              </div>
                           </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                           <div class="user-img img-fluid"><img src="{{ asset('template/images/user/04.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                           <div class="media-support-info ml-3">
                              <h6>Robin Banks</h6>
                              <p class="badge bagde-pill badge-primary mb-0 font-size-12">Common Area 1</p>
                           </div>
                           <div class="iq-card-header-toolbar d-flex align-items-center">
                              <div class="dropdown show">
                                 <span class="dropdown-toggle text-primary" id="dropdownMenuButton44" data-toggle="dropdown" aria-expanded="true" role="button">
                                    <i class="ri-more-2-line"></i>
                                 </span>
                                 <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#"><i class="ri-eye-line mr-2"></i>View</a>
                                    <a class="dropdown-item" href="#"><i class="ri-bookmark-line mr-2"></i>Appointment</a>
                                 </div>
                              </div>
                           </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                           <div class="user-img img-fluid"><img src="{{ asset('template/images/user/05.jpg') }}" alt="story-img" class="rounded-circle avatar-40"></div>
                           <div class="media-support-info ml-3">
                              <h6>Barry Wine</h6>
                              <p class="badge bagde-pill badge-primary mb-0 font-size-12">Common Area 1</p>
                           </div>
                           <div class="iq-card-header-toolbar d-flex align-items-center">
                              <div class="dropdown show">
                                 <span class="dropdown-toggle text-primary" id="dropdownMenuButton45" data-toggle="dropdown" aria-expanded="true" role="button">
                                    <i class="ri-more-2-line"></i>
                                 </span>
                                 <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#"><i class="ri-eye-line mr-2"></i>View</a>
                                    <a class="dropdown-item" href="#"><i class="ri-bookmark-line mr-2"></i>Appointment</a>
                                 </div>
                              </div>
                           </div>
                        </li>                            
                     </ul>
                  </div>
               </div>
            </div>
            <div class="col-lg-12">
               <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                  <div class="iq-card-header d-flex justify-content-between border-none">
                     <div class="iq-header-title">
                        <h5 class="card-title">Current Location</h5>
                     </div>
                  </div>
                  <div class="iq-card-body">
                     <div id="world-map" style="height: 200px; position: relative;"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
<script>
   var alert_num = 6;

   $(function(){
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

      var timer = setInterval(prependActivity, 5000);
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
