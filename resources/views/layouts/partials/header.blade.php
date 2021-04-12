<!-- TOP Nav Bar -->
<div class="iq-top-navbar">
    <div class="iq-navbar-custom">
        <div class="iq-sidebar-logo">
            <div class="top-logo">
                <a href="index.html" class="logo">
                    <img src="{{ asset('img/icons/wecare.png') }}" alt="logo">
                <span>WECare</span>
                </a>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light p-0">
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
                        @case('map')
                            <li class="breadcrumb-item active"><a href="{{ route('map.index') }}"><i class="ri-map-2-line mr-1 float-left"></i>Location Management</a></li>
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
                            @break
                        @case('reports')
                            <li class="breadcrumb-item active"><a href="{{ route('reports.index') }}"><i class="ri-file-chart-line mr-1 float-left"></i>Reports</a></li>
                            @break
                        @case('settings')
                            <li class="breadcrumb-item active"><a href="{{ route('settings.index') }}"><i class="ri-settings-4-line mr-1 float-left"></i>Settings</a></li>
                            @break
                        @case('users')
                            <li class="breadcrumb-item active"><a href="{{ route('settings.index') }}"><i class="ri-settings-4-line mr-1 float-left"></i>Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Members</li>
                            @break
                        @default
                            <li class="breadcrumb-item active"><a href="{{ route('home') }}"><i class="ri-home-4-line mr-1 float-left"></i>Dashboard</a></li>
                    @endswitch

                    @switch(Request::segment(2))
                        @case('create')
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                            @break
                    @endswitch
                </ol>
            </nav>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            </div>
            <ul class="navbar-list">
                <li>
                <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center bg-primary rounded">
                    <img src="{{ asset('template/images/user/1.jpg') }}" class="img-fluid rounded mr-3" alt="user">
                    <div class="caption">
                        <h6 class="mb-0 line-height text-white">{{ Auth::user()->full_name }}</h6>
                    </div>
                </a>
                <div class="iq-sub-dropdown iq-user-dropdown">
                    <div class="iq-card shadow-none m-0">
                        <div class="iq-card-body p-0 ">
                            <div class="bg-primary p-3">
                            <h5 class="mb-0 text-white line-height">Hello {{ Auth::user()->full_name }}</h5>
                            </div>
                            <a href="{{ route('settings.index') }}" class="iq-sub-card iq-bg-primary-hover">
                            <div class="media align-items-center">
                                <div class="rounded iq-card-icon iq-bg-primary">
                                    <i class="ri-file-user-line"></i>
                                </div>
                                <a href="profile.html" class="iq-sub-card iq-bg-primary-hover">
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
        var imagesUrl = "{{ asset("template/images/user/") }}";
    </script>
