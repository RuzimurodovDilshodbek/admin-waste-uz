<aside class="main-sidebar elevation-4">
    <!-- Brand -->
    <a href="/admin" class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20c4.56 0 6.91-4.54 9.64-8.89C19.13 8.49 20.5 5.1 20.5 5.1S16 6 17 8zm-7.63 8.46C8.87 18.3 8 19.4 8 19.4s-1-1.5-1.5-3a11.85 11.85 0 01-.5-3 5.5 5.5 0 015.5 5.5 5.82 5.82 0 01-.13.56z"/>
            </svg>
        </div>
        <div class="sidebar-brand-text">
            {{ trans('panel.site_title') }}
            <small>Admin Panel</small>
        </div>
    </a>

    <!-- Sidebar scroll area -->
    <div class="sidebar">

        @can('post_create')
            <a href="{{ route('admin.posts.create') }}" class="sidebar-quick-create">
                <i class="fas fa-plus"></i>
                <span>Yangi maqola</span>
            </a>
        @endcan

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
                       href="{{ route('admin.home') }}">
                        <i class="fas fa-fw fa-th-large nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- === KONTENT BO'LIMI === -->
                <div class="sidebar-section-label">Kontent</div>

                @can('section_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.postGetSectionId', ['id' => 1]) }}"
                           class="nav-link {{ request()->is('admin/post*') && (Request::get('id') == 1 || Request::get('section_id') == 1) ? 'active' : '' }}">
                            <i class="fas fa-fw fa-newspaper nav-icon"></i>
                            <p>Yangiliklar</p>
                        </a>
                    </li>
                @endcan

                @can('section_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.postGetSectionId', ['id' => 2]) }}"
                           class="nav-link {{ request()->is('admin/post*') && (Request::get('id') == 2 || Request::get('section_id') == 2) ? 'active' : '' }}">
                            <i class="fas fa-fw fa-bullhorn nav-icon"></i>
                            <p>E'lonlar</p>
                        </a>
                    </li>
                @endcan

                @can('section_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.postGetSectionId', ['id' => 3]) }}"
                           class="nav-link {{ request()->is('admin/post*') && (Request::get('id') == 3 || Request::get('section_id') == 3) ? 'active' : '' }}">
                            <i class="fas fa-fw fa-video nav-icon"></i>
                            <p>Kuzatuv kameralari</p>
                        </a>
                    </li>
                @endcan

                @can('section_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.sections.index') }}"
                           class="nav-link {{ request()->is('admin/sections') || request()->is('admin/sections/*') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-layer-group nav-icon"></i>
                            <p>Bo'limlar</p>
                        </a>
                    </li>
                @endcan

                <!-- === TASHKILOT BO'LIMI === -->
                <div class="sidebar-divider"></div>
                <div class="sidebar-section-label">Tashkilot</div>

                @can('statistic_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.statistics.index') }}"
                           class="nav-link {{ request()->is('admin/statistics*') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-chart-bar nav-icon"></i>
                            <p>Statistikalar</p>
                        </a>
                    </li>
                @endcan

                @can('tutor_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.managementPersons.index') }}"
                           class="nav-link {{ request()->is('admin/managementPersons*') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-user-tie nav-icon"></i>
                            <p>Boshqaruv xodimlari</p>
                        </a>
                    </li>
                @endcan

                <!-- === TIZIM BO'LIMI === -->
                @can('user_management_access')
                    <div class="sidebar-divider"></div>
                    <div class="sidebar-section-label">Tizim</div>

                    <li class="nav-item has-treeview
                        {{ request()->is('admin/permissions*') ? 'menu-open' : '' }}
                        {{ request()->is('admin/roles*') ? 'menu-open' : '' }}
                        {{ request()->is('admin/users*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle
                            {{ request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*') ? 'active' : '' }}"
                           href="#">
                            <i class="fas fa-fw fa-users-cog nav-icon"></i>
                            <p>
                                Foydalanuvchilar
                                <i class="right fas fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}"
                                       class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                        <i class="fas fa-fw fa-user nav-icon"></i>
                                        <p>Foydalanuvchilar</p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}"
                                       class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                        <i class="fas fa-fw fa-shield-alt nav-icon"></i>
                                        <p>Rollar</p>
                                    </a>
                                </li>
                            @endcan
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.permissions.index') }}"
                                       class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                                        <i class="fas fa-fw fa-unlock-alt nav-icon"></i>
                                        <p>Ruxsatlar</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Parol & Chiqish -->
                <div class="sidebar-divider"></div>

                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password*') ? 'active' : '' }}"
                               href="{{ route('profile.password.edit') }}">
                                <i class="fas fa-fw fa-key nav-icon"></i>
                                <p>Parolni o'zgartirish</p>
                            </a>
                        </li>
                    @endcan
                @endif

                <li class="nav-item sidebar-logout">
                    <a href="#" class="nav-link"
                       onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <i class="fas fa-fw fa-sign-out-alt nav-icon"></i>
                        <p>Chiqish</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
