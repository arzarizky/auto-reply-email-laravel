<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="bx bx-menu bx-sm"></i>
    </a>
</div>

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    <ul class="navbar-nav flex-row align-items-center ms-auto">

        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                    @if (Auth::user()->avatar === null)
                        <img src="{{ asset('template') }}/assets/img/avatars/avatar-1.png" alt="Avatar"
                            class="w-px-40 rounded-circle" />
                    @else
                        <img src="{{ Auth::user()->getPicAvatarAdmin() }}" alt="Avatar"
                            class="w-px-40 rounded-circle" />
                    @endif
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    @if (Auth::user()->avatar === null)
                                        <img src="{{ asset('template') }}/assets/img/avatars/avatar-1.png"
                                            alt="Avatar" class="w-px-40 rounded-circle" />
                                    @else
                                        <img src="{{ Auth::user()->getPicAvatarAdmin() }}" alt="Avatar"
                                            class="w-px-40 rounded-circle" />
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-medium d-block">{{ Auth::user()->name }}</span>
                                <small class="text-muted">{{ Auth::user()->role->name }}</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                    </a>
                </li>
            </ul>
        </li>
        <!--/ User -->
    </ul>
</div>
