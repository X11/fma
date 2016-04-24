<nav class="header has-shadow">
    <div class="container">
        <div class="header-left">
            @if (Request::is('admin*'))
                <a class="header-tab {{ Request::is('admin/user*') ? 'is-active' : '' }}" href="{{ url('/admin/user') }}">Users</a>
                <a class="header-tab {{ Request::is('admin/update*') ? 'is-active' : '' }}" href="{{ url('/admin/update') }}">Updates</a>
            @elseif (Request::is('account*'))
                <a class="header-tab {{ Request::is('account/setting*') ? 'is-active' : '' }}" href="{{ url('/account/setting') }}">Settings</a>
                <a class="header-tab" href="{{ url('/profile', [Auth::user()->id]) }}">Profile</a>
            @endif
        </div>
    </div>
</nav>
