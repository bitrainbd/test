<nav class="app-header navbar navbar-expand bg-body" id="navigation" tabindex="-1">
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Start Navbar Links-->
    <ul class="navbar-nav" role="navigation" aria-label="Navigation 1">
        <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
            </a>
        </li>
        <li class="nav-item d-none d-md-block">
            <a href="#" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-md-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
    </ul>
    <!--end::Start Navbar Links-->

    <!--begin::End Navbar Links-->
    <ul class="navbar-nav ms-auto" role="navigation" aria-label="Navigation 2">
        <!--begin::User Menu Dropdown-->
        <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            @php
                $user = auth()->user();
            @endphp
            @if ($user->profile?->avatar_url)
                <img src="{{ Storage::url($user->profile->avatar_url) }}"
                        alt="{{ $user->name }}"
                        class="user-image rounded-circle shadow"
                        width="38" height="38"
                        style="object-fit:cover;">
            @else
                <span class="avatar-placeholder rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center text-white"
                        style="width:30px;height:30px;font-size:.95rem;font-weight:600;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
            @endif

            <span class="d-none d-md-inline">{{Auth::user()->name}}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
            <li class="user-header text-bg-primary">
                @if ($user->profile?->avatar_url)
                    <img src="{{ Storage::url($user->profile->avatar_url) }}"
                            alt="{{ $user->name }}"
                            class="user-image rounded-circle shadow"
                            width="38" height="38"
                            style="object-fit:cover;">
                @else
                    <span class="avatar-placeholder rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center text-white"
                            style="width:50px;height:50px;font-size:1.5rem;font-weight:700;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                @endif
                <p>
                    {{$user->name}} - {{$user->getRoleNames()->first();}}
                    <small>Member since {{ \Carbon\Carbon::parse($user->created_at)->format('F Y') }}</small>
                </p>
            </li>
            <li class="user-body">
                <!--begin::Row-->
                <div class="row">
                <div class="col-4 text-center">
                    <a href="#">Followers</a>
                </div>
                <div class="col-4 text-center">
                    <a href="#">Sales</a>
                </div>
                <div class="col-4 text-center">
                    <a href="#">Friends</a>
                </div>
                </div>
                <!--end::Row-->
            </li>
            <li class="user-footer">
                <a href="#" class="btn btn-outline-secondary">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger float-end">Sign out</button>
                </form>
            </li>
        </ul>
        </li>
        <!--end::User Menu Dropdown-->
    </ul>
<!--end::End Navbar Links-->
</div>
<!--end::Container-->
</nav>