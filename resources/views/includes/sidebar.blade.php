<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="{{route('dashboard')}}" class="brand-link">
            <!--begin::Brand Image-->
            {{-- <img
            src="{{asset('/img/AdminLTELogo.png')}}"
            alt="AdminLTE Logo"
            class="brand-image opacity-75 shadow"
            /> --}}
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-bold text-warning">BitRain</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
    <nav class="mt-2">
        <!--begin::Sidebar Menu-->
        <ul
            class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="navigation"
            aria-label="Main navigation"
            data-accordion="false"
            id="navigation">

        <li class="nav-header m-0 p-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MAIN</li>
        <li class="nav-item my-1">
            <a href="{{route('dashboard')}}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="nav-icon bi bi-gear"></i>
                <p>Dashboard</p>
            </a>
        </li>
        <div class="border-top border-secondary"></div>

        <li class="nav-header mt-3 p-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PEOPLE & Role</li>

        <li class="nav-item my-1 {{ request()->routeIs('users.index', 'users.create','users.edit') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('users.index', 'users.create') ? 'active' : '' }}">
                <i class="nav-icon bi bi-people"></i>
                <p>
                    User Management
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="margin-left: 10px; {{ request()->routeIs('users.index', 'users.create', 'users.edit') ? 'display:block' : '' }}">
                <li class="nav-item">
                    <a href="{{route('users.create')}}" class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Add User</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('users.index')}}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Users</p>
                    </a>
                </li>                                                  
            </ul>
        </li>

        <li class="nav-item mb-1 {{ request()->routeIs('roles.index', 'roles.create','roles.edit') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('roles.index', 'roles.create') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i>
                <p>
                    Role & Permissions
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="margin-left: 10px; {{ request()->routeIs('roles.index', 'roles.create', 'roles.edit') ? 'display:block' : '' }}">
                <li class="nav-item">
                    <a href="{{route('roles.create')}}" class="nav-link {{ request()->routeIs('roles.create') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Add Role</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('roles.index')}}" class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Roles</p>
                    </a>
                </li>                                                  
            </ul>
        </li>

        <div class="border-top border-secondary"></div>



        <li class="nav-header mt-3 p-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Academics</li>
        <li class="nav-item mt-1">
            <a href="#" class="nav-link">
                <i class="nav-icon bi bi-people"></i>
                <p>
                    Exam Management
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="margin-left: 10px">
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Create Exam</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Exams</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Exam Attempts</p>
                    </a>
                </li>                            
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon bi bi-people"></i>
                <p>
                    Questions
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="margin-left: 10px">
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Add Question</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Questions</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Question Sources</p>
                    </a>
                </li>                            
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon bi bi-people"></i>
                <p>
                    Curriculum
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="margin-left: 10px">
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Education Levels</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Subjects</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Chapters</p>
                    </a>
                </li> 
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Topics</p>
                    </a>
                </li>                            
            </ul>
        </li>
        <div class="border-top border-secondary"></div>

    
        <li class="nav-header mt-3 p-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INSTITUTIONS</li>
        <li class="nav-item mt-1">
            <a href="#" class="nav-link">
                <i class="nav-icon bi bi-people"></i>
                <p>
                    Institutions
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="margin-left: 10px">
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Boards</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Colleges</p>
                    </a>
                </li>   
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Universities</p>
                    </a>
                </li>                                                  
            </ul>
        </li>
        <div class="border-top border-secondary"></div>

        <li class="nav-header mt-3 p-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FINANCE & INFO</li>
        <li class="nav-item mt-1">
            <a href="#" class="nav-link">
                <i class="nav-icon bi bi-people"></i>
                <p>
                    Subscriptions
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="margin-left: 10px">
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Plans</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>User Subscriptions</p>
                    </a>
                </li>                                                                 
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon bi bi-people"></i>
                <p>
                    Notices
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="margin-left: 10px">
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Create Notice</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>All Notices</p>
                    </a>
                </li>                                                                 
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="nav-icon bi bi-gear"></i>
            <p>Leaderboard</p>
            </a>
        </li>

        <div class="border-top border-secondary"></div>
        
        <li class="nav-header mt-3 p-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;System</li>
        <li class="nav-item my-1">
            <a href="{{route('settings.general')}}" class="nav-link">
            <i class="nav-icon bi bi-gear"></i>
            <p>Settings</p>
            </a>
        </li>
        {{-- <div class="border-top border-secondary"></div> --}}

    </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>