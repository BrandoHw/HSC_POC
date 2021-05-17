<!-- Sidebar -->
<div class="iq-sidebar">
    <div class="iq-sidebar-logo d-flex justify-content-between">
        <a href="../">
            <img src="{{ asset('img/icons/wecare.png') }}" alt="logo">
            <span>WeCare</span>
        </a>
        <div class="iq-menu-bt-sidebar">
            <div class="iq-menu-bt align-self-center">
                <div class="wrapper-menu">
                    <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                    <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div id="sidebar-scrollbar">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">
                <li class="{{ Request::is('/') ? 'sidebar-item active' : 'sidebar-item' }}">
                    <a href="{{ route('home') }}" class="iq-waves-effect"><i class="ri-home-4-fill"></i><span>Dashboard</span></a>
                </li>
                @if(Request::segment(1) === 'residents')
                    @canany(['resident-list', 'attendance-list'])
                    <li class="active">
                        <a href="#menu-level" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="true"><i class="ri-user-3-fill"></i><span>Residents</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="menu-level" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @can('resident-list')
                            <li class="active" ><a href="{{ route('residents.index') }}"><i class="ri-profile-line"></i>Profile</a></li>
                            @endcan
                            @can('attendance-list')
                            <li class=""><a href="{{ route('attendance.index') }}"><i class="ri-checkbox-line"></i>Attendance</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                @elseif(Request::segment(1) === 'attendance')
                    @canany(['resident-list', 'attendance-list'])
                    <li class="active">
                        <a href="#menu-level" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="true"><i class="ri-user-3-fill"></i><span>Residents</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="menu-level" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @can('resident-list')
                            <li class="" ><a href="{{ route('residents.index') }}"><i class="ri-profile-line"></i>Profile</a></li>
                            @endcan
                            @can('attendance-list')
                            <li class="active"><a href="{{ route('attendance.index') }}"><i class="ri-checkbox-line"></i>Attendance</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                @else
                    @canany(['resident-list', 'attendance-list'])
                    <li class="">
                        <a href="#menu-level" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-user-3-fill"></i><span>Residents</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="menu-level" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            @can('resident-list')
                            <li class="" ><a href="{{ route('residents.index') }}"><i class="ri-profile-line"></i>Profile</a></li>
                            @endcan
                            @can('attendance-list')
                            <li class=""><a href="{{ route('attendance.index') }}"><i class="ri-checkbox-line"></i>Attendance</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                @endif
                @can('location-list')
                <li class="{{ Request::segment(1) === 'locations' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('locations.index') }}" class="iq-waves-effect"><i class="ri-map-2-fill"></i><span>Locations</span></a></li>
                @endcan
                @can('gateway-list')
                <li class="{{ Request::segment(1) === 'gateways' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('gateways.index') }}" class="iq-waves-effect"><i class="ri-base-station-fill"></i><span>Gateways</span></a></li>
                @endcan
                @can('beacon-list')
                <li class="{{ Request::segment(1) === 'beacons' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('beacons.index') }}" class="iq-waves-effect"><i class="ri-share-fill"></i><span>Beacons</span></a></li>
                @endcan
                @can('policy-list')
                <li class="{{ Request::segment(1) === 'policies' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('policies.index') }}" class="iq-waves-effect"><i class="ri-message-fill"></i><span>Policies</span></a></li>
                @endcan
                @can('alert-list')
                <li class="{{ Request::segment(1) === 'alerts' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('alerts.index') }}" class="iq-waves-effect"><i class="ri-alarm-warning-fill"></i><span>Alerts</span></a></li>
                @endcan
                @can('tracking-list')
                <li class="{{ Request::segment(1) === 'tracking' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('tracking.index') }}" class="iq-waves-effect"><i class="ri-map-pin-user-fill"></i><span>Tracking</span></a></li>
                @endcan
                @can('report-list')
                <li class="{{ Request::segment(1) === 'reports' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('reports.index') }}" class="iq-waves-effect"><i class="ri-file-chart-fill"></i><span>Reports</span></a></li>
                @endcan
                <li class="{{ Request::segment(1) === 'settings' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('settings.index') }}" class="iq-waves-effect"><i class="ri-settings-4-fill"></i><span>Settings</span></a></li>
            </ul>
        </nav>
        <div class="p-3"></div>
    </div>
</div>
