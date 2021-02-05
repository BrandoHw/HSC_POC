<!-- Sidebar -->
<div class="iq-sidebar">
    <div class="iq-sidebar-logo d-flex justify-content-between">
        <a href="../index.html">
            <img src="{{ asset('img/icons/wecare.png') }}" alt="logo">
            <span>WECare</span>
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
                <!-- Proposed nav item -->
                <li class="{{ Request::is('/') ? 'sidebar-item active' : 'sidebar-item' }}">
                    <a href="{{ route('home') }}" class="iq-waves-effect"><i class="ri-home-4-fill"></i><span>Dashboard</span></a>
                </li>
                <li class="{{ Request::segment(1) === 'residents' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('residents.index') }}" class="iq-waves-effect"><i class="ri-user-3-fill"></i><span>Residents</span></a></li>
                <li class="{{ Request::segment(1) === 'map' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('map.index') }}" class="iq-waves-effect"><i class="ri-map-2-fill"></i><span>Locations</span></a></li>
                <li class="{{ Request::segment(1) === 'readers' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('readers.index') }}" class="iq-waves-effect"><i class="ri-base-station-fill"></i><span>Gateways</span></a></li>
                <li class="{{ Request::segment(1) === 'tags' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('tags.index') }}" class="iq-waves-effect"><i class="ri-share-fill"></i><span>Beacons</span></a></li>
                <li class="{{ Request::segment(1) === 'policies' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('policies.index') }}" class="iq-waves-effect"><i class="ri-message-fill"></i><span>Policies</span></a></li>
                <li class="{{ Request::segment(1) === 'alerts' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="#" class="iq-waves-effect"><i class="ri-alarm-warning-fill"></i><span>Alerts</span></a></li>
                <li class="{{ Request::segment(1) === 'tracking' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('tracking.index') }}" class="iq-waves-effect"><i class="ri-map-pin-user-fill"></i><span>Tracking</span></a></li>
                <li class="{{ Request::segment(1) === 'reports' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="#" class="iq-waves-effect"><i class="ri-file-chart-fill"></i><span>Reports</span></a></li>
                <li class="{{ Request::segment(1) === 'settings' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="#" class="iq-waves-effect"><i class="ri-settings-4-fill"></i><span>Settings</span></a></li>

                <!-- From Previouse -->
                <!-- <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Previous</span></li>
                <li class="{{ Request::segment(1) === 'attendance' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="#" class="iq-waves-effect"><i class="ri-map-2-fill"></i><span>Attendance</span></a></li>
                <li class="{{ Request::segment(1) === 'clients' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('companies.index') }}" class="iq-waves-effect"><i class="ri-share-fill"></i><span>Clients</span></a></li>
                <li class="{{ Request::segment(1) === 'projects' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('projects.index') }}" class="iq-waves-effect"><i class="ri-message-fill"></i><span>Projects</span></a></li>
                <li class="{{ Request::segment(1) === 'groups' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('groups.index') }}" class="iq-waves-effect"><i class="ri-alarm-warning-fill"></i><span>Groups</span></a></li>
                <li class="{{ Request::segment(1) === 'users' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('users.index') }}" class="iq-waves-effect"><i class="ri-map-pin-user-fill"></i><span>Users</span></a></li>
                <li class="{{ Request::segment(1) === 'roles' ? 'sidebar-item active' : 'sidebar-item' }}"><a href="{{ route('roles.index') }}" class="iq-waves-effect"><i class="ri-file-chart-fill"></i><span>Roles & Permissions</span></a></li> -->
            </ul>
        </nav>
        <div class="p-3"></div>
    </div>
</div>