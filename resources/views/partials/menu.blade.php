<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <div class="d-flex align-items-center justify-content-around">
        <a href="/admin" class="brand-link">
            <span class="brand-text font-weight-light">
                {{ trans('panel.site_title') }}
            </span>
        </a>
        @can('post_create')
            <span>
                <a class="" href="{{ route('admin.posts.create') }}">
                    <i class="fa fa-plus-circle" style="transform: scale(2); color: #00b249"></i>
                </a>
            </span>
        @endcan
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs("admin.home") ? "active" : "" }}" href="{{ route("admin.home") }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>
                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/permissions*") ? "menu-open" : "" }} {{ request()->is("admin/roles*") ? "menu-open" : "" }} {{ request()->is("admin/users*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/permissions*") ? "active" : "" }} {{ request()->is("admin/roles*") ? "active" : "" }} {{ request()->is("admin/users*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.permission.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase">

                                        </i>
                                        <p>
                                            {{ trans('cruds.role.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user">

                                        </i>
                                        <p>
                                            {{ trans('cruds.user.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('post_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/posts*") || request()->is("admin/post*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/posts*") || request()->is("admin/post*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-neuter">

                            </i>
                            <p>
                                {{ trans('cruds.post.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("admin.posts.index") }}" class="nav-link {{ request()->is("admin/posts") || request()->is("admin.posts.index") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-briefcase">

                                    </i>
                                    <p>
                                        {{ 'Барчаси' }}
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                {{--                                    {{ dd(request()->is("admin/post/*")) }}--}}
                                <a href="{{ route("admin.post.archived") }}" class="nav-link {{ request()->is("admin/post/*") || request()->is("admin.post") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-briefcase"></i>
                                    <p>
                                        {{ 'Архив' }}
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
{{--                @can('section_access')--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('admin.postGetSectionId',['id' => 1]) }}" class="nav-link {{ request()->is("admin/post*") && (Request::get('id') == 1 || Request::get('section_id') == 1) ? "active" : "" }}">--}}
{{--                            <i class="fa-fw nav-icon fas fa-briefcase"></i>--}}
{{--                            <p>Xabarlar</p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endcan--}}
{{--                @can('section_access')--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('admin.postGetSectionId',['id' => 5]) }}" class="nav-link {{ request()->is("admin/post*") && (Request::get('id') == 5 || Request::get('section_id') == 5) ? "active" : "" }}">--}}
{{--                            <i class="fa-fw nav-icon fas fa-briefcase"></i>--}}
{{--                            <p>Eko muammo</p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endcan--}}
{{--                @can('section_access')--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('admin.postGetSectionId',['id' => 14]) }}" class="nav-link {{ request()->is("admin/post*") && (Request::get('id') == 14 || Request::get('section_id') == 14) ? "active" : "" }}">--}}
{{--                            <i class="fa-fw nav-icon fas fa-briefcase"></i>--}}
{{--                            <p>Eko volontiyorlik</p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endcan--}}
                @can('statistic_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.statistics.index") }}" class="nav-link {{ request()->is("admin/statistics") || request()->is("admin.posts.index") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs"></i>
                            <p>
                                Statistikalar
                            </p>
                        </a>
                    </li>
                @endcan




                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}" href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key nav-icon">
                                </i>
                                <p>
                                    {{ trans('global.change_password') }}
                                </p>
                            </a>
                        </li>
                    @endcan
                @endif
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon">

                            </i>
                            <p>{{ trans('global.logout') }}</p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
