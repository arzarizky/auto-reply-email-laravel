<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Menu</span>
</li>

<li class="menu-item {{ request()->segment(1) == 'auto-reply' ? 'active' : '' }}">
    <a href="{{ route('auto-reply') }}" class="menu-link">
        <i class='menu-icon tf-icons bx bx-envelope'></i>
        <div data-i18n="User Manager">Auto Reply</div>
    </a>
</li>

<li class="menu-item {{ request()->segment(1) == 'email-received' ? 'active' : '' }}">
    <a href="{{ route('email-received') }}" class="menu-link">
        <i class='menu-icon tf-icons bx bx-mail-send'></i>
        <div data-i18n="User Manager">Email Received</div>
    </a>
</li>

<li class="menu-item {{ request()->segment(1) == 'mail-setting' ? 'active' : '' }}">
    <a href="{{ route('mail-setting') }}" class="menu-link">
        <i class='menu-icon tf-icons bx bxl-mailchimp'></i>
        <div data-i18n="User Manager">Mail Server Setting</div>
    </a>
</li>

@if (Auth::user()->isAdmin())
    <li class="menu-item {{ request()->segment(1) == 'user-manager' ? 'active' : '' }}">
        <a href="{{ route('user-manager') }}" class="menu-link">
            <i class='menu-icon tf-icons bx bxs-user-account'></i>
            <div data-i18n="User Manager">User Manager</div>
        </a>
    </li>
@endif
