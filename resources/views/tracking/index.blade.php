@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="iq-card">
            <div class="iq-card-body chat-page p-0">
                <div class="chat-data-block">
                <div class="row">
                    <div class="col-lg-3 chat-data-left scroller">
                        <div class="chat-search pt-3 pl-3">
                        <div class="chat-searchbar mt-1">
                                <div class="form-group chat-search-data m-0">
                                    <input type="text" class="form-control round" id="chat-search" placeholder="Search">
                                    <i class="ri-search-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="chat-sidebar-channel scroller mt-4 pl-3" style="height: calc(100vh - 16rem) !important">
                        <ul class="iq-chat-ui nav flex-column nav-pills">
                            <li>
                            <a  data-toggle="pill" href="#chatbox1">
                                <div class="d-flex align-items-center">
                                    <div class="avatar mr-3">
                                    <img src="{{ asset('template/images/user/05.jpg') }}" alt="chatuserimage" class="avatar-50 ">
                                    </div>
                                    <div class="chat-sidebar-name">
                                    <h6 class="mb-0">Anna Sthesia</h6>
                                    </div>
                                </div>
                            </a>
                            </li>
                            <li>
                            <a  data-toggle="pill" href="#chatbox2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar mr-3">
                                    <img src="{{ asset('template/images/user/06.jpg') }}" alt="chatuserimage" class="avatar-50 ">
                                    </div>
                                    <div class="chat-sidebar-name">
                                    <h6 class="mb-0">Brock Lee</h6>
                                    </div>
                                </div>
                            </a>
                            </li>
                        </ul>
                        <ul class="iq-chat-ui nav flex-column nav-pills">
                            <li>
                            <a  data-toggle="pill" href="#chatbox3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar mr-3">
                                    <img src="{{ asset('template/images/user/07.jpg') }}" alt="chatuserimage" class="avatar-50 ">
                                    </div>
                                    <div class="chat-sidebar-name">
                                    <h6 class="mb-0">Dan Druff</h6>
                                    </div>
                                </div>
                            </a>
                            </li>
                            <li>
                            <a  data-toggle="pill" href="#chatbox4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar mr-3">
                                    <img src="{{ asset('template/images/user/08.jpg') }}" alt="chatuserimage" class="avatar-50 ">
                                    </div>
                                    <div class="chat-sidebar-name">
                                    <h6 class="mb-0">Hans Olo</h6>
                                    </div>
                                </div>
                            </a>
                            </li>
                            <li>
                            <a  data-toggle="pill" href="#chatbox5">
                                <div class="d-flex align-items-center">
                                    <div class="avatar mr-3">
                                    <img src="{{ asset('template/images/user/09.jpg') }}" alt="chatuserimage" class="avatar-50 ">
                                    </div>
                                    <div class="chat-sidebar-name">
                                    <h6 class="mb-0">Lynn Guini</h6>
                                    </div>
                                </div>
                            </a>
                            </li>
                            <li>
                            <a  data-toggle="pill" href="#chatbox6">
                                <div class="d-flex align-items-center">
                                    <div class="avatar mr-3">
                                    <img src="{{ asset('template/images/user/10.jpg') }}" alt="chatuserimage" class="avatar-50 ">
                                    </div>
                                    <div class="chat-sidebar-name">
                                    <h6 class="mb-0">Paul Molive</h6>
                                    </div>
                                </div>
                            </a>
                            </li>
                            <li>
                            <a  data-toggle="pill" href="#chatbox7">
                                <div class="d-flex align-items-center">
                                    <div class="avatar mr-3">
                                    <img src="{{ asset('template/images/user/05.jpg') }}" alt="chatuserimage" class="avatar-50 ">
                                    </div>
                                    <div class="chat-sidebar-name">
                                    <h6 class="mb-0">Paige Turner</h6>
                                    </div>
                                </div>
                            </a>
                            </li>
                            <li>
                            <a  data-toggle="pill" href="#chatbox8">
                                <div class="d-flex align-items-center">
                                    <div class="avatar mr-3">
                                    <img src="{{ asset('template/images/user/06.jpg') }}" alt="chatuserimage" class="avatar-50 ">
                                    </div>
                                    <div class="chat-sidebar-name">
                                    <h6 class="mb-0">Barb Ackue</h6>
                                    </div>
                                </div>
                            </a>
                            </li>
                            <li>
                            <a  data-toggle="pill" href="#chatbox9">
                                <div class="d-flex align-items-center">
                                    <div class="avatar mr-3">
                                    <img src="{{ asset('template/images/user/07.jpg') }}" alt="chatuserimage" class="avatar-50 ">
                                    </div>
                                    <div class="chat-sidebar-name">
                                    <h6 class="mb-0">Maya Didas</h6>
                                    </div>
                                </div>
                            </a>
                            </li>
                            <li>
                            <a  data-toggle="pill" href="#chatbox10">
                                <div class="d-flex align-items-center">
                                    <div class="avatar mr-3">
                                    <img src="{{ asset('template/images/user/08.jpg') }}" alt="chatuserimage" class="avatar-50 ">
                                    </div>
                                    <div class="chat-sidebar-name">
                                    <h6 class="mb-0">Monty Carlo</h6>
                                    </div>
                                </div>
                            </a>
                            </li>
                        </ul>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mt-2">
                        <ul class="nav nav-tabs justify-content-right" id="myTab-2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="description-tab-justify" data-toggle="tab" href="#description" role="tab" aria-selected="true">Current Location</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="specifications-tab-justify" data-toggle="tab" href="#specifications" role="tab"  aria-selected="false">Location History</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-3">
                            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab-justify">
                                <div id="world-map" style="height: 500px; position: relative;"></div>
                            </div>
                            <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab-justify">
                                <div class="row">
                                
                                    <div class="col-lg-8">
                                        <div id="world-map-copy" style="height: 500px; position: relative;"></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group row">
                                            <label class="control-label col-sm-2 align-self-center mb-0" for="from">From:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="date-start" style="background-color: white"/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="control-label col-sm-2 align-self-center mb-0" for="to">To:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="date-end" style="background-color: white"/>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="iq-bg-primary pl-3 pr-3 pt-2 pb-2 ml-2 mr-2 rounded">History Record</div>
                                        <div class="chat-sidebar-channel scroller mt-4 pl-3" style="height: calc(100vh - 29rem) !important">
                                            <ul class="iq-timeline">
                                                <li>
                                                    <div class="timeline-dots"></div>
                                                    <h6 class="float-left mb-1">Jan 15</h6>
                                                    <div class="d-inline-block w-100">
                                                        <p class="mb-0">Location 1</p>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="timeline-dots"></div>
                                                    <h6 class="float-left mb-1">Jan 22</h6>
                                                    <div class="d-inline-block w-100">
                                                        <p class="mb-0">Location 2</p>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="timeline-dots"></div>
                                                    <h6 class="float-left mb-1">Feb 09</h6>
                                                    <div class="d-inline-block w-100">
                                                        <p class="mb-0">Location 3</p>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="timeline-dots"></div>
                                                    <h6 class="float-left mb-1">March 05</h6>
                                                    <div class="d-inline-block w-100">
                                                        <p class="mb-0">Location 4</p>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="timeline-dots"></div>
                                                    <h6 class="float-left mb-1">April 02</h6>
                                                    <div class="d-inline-block w-100">
                                                        <p class="mb-0">Location 5</p>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="timeline-dots"></div>
                                                    <h6 class="float-left mb-1">April 17</h6>
                                                    <div class="d-inline-block w-100">
                                                        <p class="mb-0">Location 6</p>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="timeline-dots"></div>
                                                    <h6 class="float-left mb-1">April 25</h6>
                                                    <div class="d-inline-block w-100">
                                                        <p class="mb-0">Location 7</p>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="timeline-dots"></div>
                                                    <h6 class="float-left mb-1">May 15</h6>
                                                    <div class="d-inline-block w-100">
                                                        <p class="mb-0">Location 8</p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
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
    $(function() {
        $('#date-start').flatpickr(
            {

            }
        );
        $('#date-end').flatpickr(
            {
            }
        );
    });
</script>
@endsection