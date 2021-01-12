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
    
            <li class="{{ Request::is('/') ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('home') }}">
                    @svg('clock', 'feather-clock align-middle')
                    <span class="align-middle">Attendance</span>
                </a>
            </li>

            @canany('user-list', 'role-list', 'project-list', 'group-list', 'company-list')
            <li class="sidebar-header">
                Manage
            </li>
            @endcan

            
            @can('map-list')
            <li class="{{ Request::segment(1) === 'map' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ url('map/1') }}">
                    @svg('map', 'feather-map align-middle')
                    <span class="align-middle">Tracking</span>
                </a>
            </li>
            @endcan


            @can('company-list')
            <li class="{{ Request::segment(1) === 'companies' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('companies.index') }}">
                    @svg('briefcase', 'feather-briefcase align-middle')
                    <span class="align-middle">Clients</span>
                </a>
            </li>
            @endcan

            @can('project-list')
            <li class="{{ Request::segment(1) === 'projects' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('projects.index') }}">
                    @svg('grid', 'feather-grid align-middle')
                    <span class="align-middle">Projects</span>
                </a>
            </li>
            @endcan

            @can('group-list')
            <li class="{{ Request::segment(1) === 'groups' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('groups.index') }}">
                    @svg('users', 'feather-users align-middle')
                    <span class="align-middle">Groups</span>
                </a>
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

            @can('role-list')
            <li class="{{ Request::segment(1) === 'roles' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('roles.index') }}">
                    @svg('lock', 'feather-lock align-middle')
                    <span class="align-middle">Roles & Permissions</span>
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
                <a class="sidebar-link" href="{{ route('readers.index') }}">
                    @svg('airplay', 'feather-airplay align-middle')
                    <span class="align-middle">Readers</span>
                </a>
            </li>
            @endcan

            @can('tag-list')
            <li class="{{ Request::segment(1) === 'tags' ? 'sidebar-item active' : 'sidebar-item' }}">
                <a class="sidebar-link" href="{{ route('tags.index') }}">
                    @svg('tablet', 'feather-tablet align-middle')
                    <span class="align-middle">Tags</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
</nav>