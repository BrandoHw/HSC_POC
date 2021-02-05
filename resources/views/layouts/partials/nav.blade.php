<nav id="sidebar" class="sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('home') }}">
            <span class="align-middle">WECare</span>
        </a>
        <!-- Navigation Item -->
        <ul class="sidebar-nav">
            <!-- <li class="{{ Request::is('/') ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('home') }}">
                    @svg('layers', 'feather-layers align-middle')
                    <span class="align-middle">Dashboard</span>
                </a>
            </li> -->
    
            @can('map-list')
            <li class="{{ Request::segment(1) === 'map' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ url('map/1') }}">
                    @svg('map', 'feather-map align-middle')
                    <span class="align-middle">Tracking</span>
                </a>
            </li>
            @endcan


            @canany('user-list', 'role-list', 'project-list', 'group-list', 'company-list')
            <li class="sidebar-header">
                Manage
            </li>
            @endcan


            @can('user-list')
            <li class="{{ Request::segment(1) === 'users' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('users.index') }}">
                    @svg('user', 'feather-user align-middle')
                    <span class="align-middle">Users</span>
                </a>
            </li>
            @endcan

            @can('policy-list')
            <li class="{{ Request::segment(1) === 'policy' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('policy.index') }}">
                    @svg('book', 'feather-book align-middle')
                    <span class="align-middle">Policy</span>
                </a>
            </li>
            @endcan

            @can('role-list')
            <li class="{{ Request::segment(1) === 'roles' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('roles.index') }}">
                    @svg('lock', 'feather-lock align-middle')
                    <span class="align-middle">Roles & Permissions</span>
                </a>
            </li>
            @endcan

            @can('floor-list')
            <li class="{{ Request::segment(1) === 'floors' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('floors.index') }}">
                    @svg('layout', 'feather-layout align-middle')
                    <span class="align-middle">Floors</span>
                </a>
            </li>
            @endcan

            @canany('tag-list', 'reader-list' )
            <li class="sidebar-header">
                Devices
            </li>
            @endcan

            @can('reader-list')
            <li class="{{ Request::segment(1) === 'readers' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('gateways.index') }}">
                    @svg('airplay', 'feather-airplay align-middle')
                    <span class="align-middle">Gateways</span>
                </a>
            </li>
            @endcan

            @can('tag-list')
            <li class="{{ Request::segment(1) === 'tags' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('beacons.index') }}">
                    @svg('tablet', 'feather-tablet align-middle')
                    <span class="align-middle">Beacons</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
</nav>