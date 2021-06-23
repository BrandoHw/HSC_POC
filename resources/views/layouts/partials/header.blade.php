<!-- TOP Nav Bar -->

<style>
div#notif-bell { height: 30px; width: 30px; }
div#notif-bell svg path { stroke: var(--iq-primary); }
.iq-sub-card { cursor: pointer;}
</style>
<div class="iq-top-navbar">
    <div class="iq-navbar-custom">
        <div class="iq-sidebar-logo">
            <div class="top-logo">
                <a href="index.html" class="logo">
                    <img src="{{ asset('img/icons/wecare.png') }}" alt="logo">
                    <span style="text-transform: none !important">WeCare</span>
                </a>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="iq-menu-bt align-self-center" style="position:unset; margin-left:1.5rem; margin-right:-0.125rem; background: var(--iq-body-bg)">
                <div class="wrapper-menu">
                <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                </div>
            </div>
            <nav aria-label="breadcrumb" style="padding-left:20px">
                <ol class="breadcrumb iq-bg-primary mb-0">
                    @switch(Request::segment(1))
                        @case('residents')
                            <li class="breadcrumb-item active"><a href="{{ route('residents.index') }}"><i class="ri-user-3-line mr-1 float-left"></i>Resident Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                            @break
                        @case('attendance')
                            <li class="breadcrumb-item active"><a href="{{ route('residents.index') }}"><i class="ri-user-3-line mr-1 float-left"></i>Resident Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                            @break
                        @case('locations')
                            <li class="breadcrumb-item active"><a href="{{ route('locations.index') }}"><i class="ri-map-2-line mr-1 float-left"></i>Location Management</a></li>
                            @break
                        @case('map')
                            <li class="breadcrumb-item active"><a href="{{ route('locations.index') }}"><i class="ri-map-2-line mr-1 float-left"></i>Location Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Map & Zoning</li>
                            @break
                        @case('floors')
                            <li class="breadcrumb-item active"><a href="{{ route('locations.index') }}"><i class="ri-map-2-line mr-1 float-left"></i>Location Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Floor</li>
                            @break
                        @case('gateways')
                            <li class="breadcrumb-item active"><a href="{{ route('gateways.index') }}"><i class="ri-base-station-line mr-1 float-left"></i>Gateway Management</a></li>
                            @break
                        @case('beacons')
                            <li class="breadcrumb-item active"><a href="{{ route('beacons.index') }}"><i class="ri-share-line mr-1 float-left"></i>Beacon Management</a></li>
                            @break
                        @case('policies')
                            <li class="breadcrumb-item active"><a href="{{ route('policies.index') }}"><i class="ri-message-line mr-1 float-left"></i>Policy Management</a></li>
                            @break
                        @case('alerts')
                            <li class="breadcrumb-item active"><a href="{{ route('alerts.index') }}"><i class="ri-alarm-warning-line mr-1 float-left"></i>Alerts</a></li>
                            @break
                        @case('tracking')
                            <li class="breadcrumb-item active"><a href="{{ route('tracking.index') }}"><i class="ri-map-pin-user-line mr-1 float-left"></i>Tracking</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Current Location</li>
                            @break
                        @case('reports')
                            <li class="breadcrumb-item active"><a href="{{ route('reports.index') }}"><i class="ri-file-chart-line mr-1 float-left"></i>Reports</a></li>
                            @break
                        @case('settings')
                            <li class="breadcrumb-item active"><a href="{{ route('settings.index') }}"><i class="ri-settings-4-line mr-1 float-left"></i>Settings</a></li>
                            @break
                        @case('users')
                            <li class="breadcrumb-item active"><a href="{{ route('settings.index') }}"><i class="ri-settings-4-line mr-1 float-left"></i>Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Users</li>
                            @break
                        @case('roles')
                            <li class="breadcrumb-item active"><a href="{{ route('settings.index') }}"><i class="ri-settings-4-line mr-1 float-left"></i>Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Roles & Permissions</li>
                            @break
                        @default
                            <li class="breadcrumb-item active"><a href="{{ route('home') }}"><i class="ri-home-4-line mr-1 float-left"></i>Dashboard</a></li>
                    @endswitch

                    @if(Request::segment(1) != 'map')
                        @if(Request::segment(2) == 'create')
                            <li class="breadcrumb-item active" aria-current="page">Add New</li>
                        @elseif ((int)Request::segment(2) > 0)
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        @endif
                    @endif
                </ol>
            </nav>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto navbar-list">
                    <li id ="notif-li" class="nav-item">
                        <a href="#" id = "notif-a" class="search-toggle iq-waves-effect">
                            <div id="notif-bell" style="width: 20px; height: 20px"></div>
                            <span id ="notif-danger-dots"class="bg-danger dots"></span>
                        </a>
                        <div class="iq-sub-dropdown">
                            <div class="iq-card shadow-none m-0">
                                <div id ="notification-card" class="iq-card-body p-0 ">
                                    <div class="bg-primary p-3">
                                        <h5 class="mb-0 text-white">All Notifications<small id = "notif-count" class="badge  badge-light float-right pt-1"></small></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <ul class="navbar-list">
                <li>
                    <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center bg-primary rounded">
                        <img src="{{ Auth::user()->image_url === null ? 
                            asset('img/avatars/default-profile-m.jpg') : Auth::user()->getThumbnail() }}"  class="img-fluid rounded mr-3" alt="user">
                        <div class="caption">
                            <h6 class="mb-0 line-height text-white">{{ Auth::user()->full_name }}</h6>
                        </div>
                    </a>
                    <div class="iq-sub-dropdown iq-user-dropdown">
                        <div class="iq-card shadow-none m-0">
                            <div class="iq-card-body p-0 ">
                                <a href="{{ route('settings.index') }}" class="iq-sub-card iq-bg-primary-hover">
                                    <div class="media align-items-center">
                                        <div class="rounded iq-card-icon iq-bg-primary">
                                            <i class="ri-file-user-line"></i>
                                        </div>
                                        <div class="media-body ml-3">
                                            <h6 class="mb-0 ">My Profile</h6>
                                            <p class="mb-0 font-size-12">View personal profile details.</p>
                                        </div>
                                    </div>
                                </a>
                                <div class="d-inline-block w-100 text-center p-3">
                                    <a class="bg-primary iq-sign-btn" href="{{ route('logout') }}" 
                                        onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                            Sign out
                                            <i class="ri-login-box-line ml-2"></i>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
    </div>
    <!-- TOP Nav Bar END -->

    <script>
        // "global" vars, built using blade
        var imagesUrl = "{{ asset("css/images/") }}";
    </script>
