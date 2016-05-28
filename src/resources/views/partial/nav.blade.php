<nav class="nav">
    <div class="container">
        <div class="nav-left">
            @if (Request::is('admin*'))
                <a class="nav-item is-tab {{ Request::is('admin/user*') ? 'is-active' : '' }}" href="{{ url('/admin/user') }}">Users</a>
                <a class="nav-item is-tab {{ Request::is('admin/update*') ? 'is-active' : '' }}" href="{{ url('/admin/update') }}">Updates</a>
            @elseif (Request::is('account*'))
                <a class="nav-item is-tab {{ Request::is('account/setting*') ? 'is-active' : '' }}" href="{{ url('/account/setting') }}">Settings</a>
                <a class="nav-item is-tab" href="{{ url('/profile', [Auth::user()->id]) }}">Profile</a>
            @endif
        </div>
    </div>
</nav>
