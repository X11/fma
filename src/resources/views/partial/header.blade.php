<section class="hero is-{{ $header_background }} is-small">
    <!-- Hero header: will stick at the top -->
    <div class="hero-head">
        <div class="container">
            <nav class="nav">
                <!-- Left side -->
                <div class="nav-left">
                    <a class="nav-item is-tab" href="{{ url('/') }}">FMA</a>
                </div>

                <!-- Hamburger menu (on mobile) -->
                <span class="nav-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>

                <!-- Right side -->
                <div class="nav-right nav-menu">
                    @if (Auth::check())
                        <a class="nav-item is-tab {{ Request::is('home') ? 'is-active' : '' }}" href="{{ url('/home') }}">Home</a>
                    @endif
                    <a class="nav-item is-tab {{ Request::is('serie*') ? 'is-active' : '' }}" href="{{ url('/serie') }}">Series</a>
                    <a class="nav-item is-tab {{ Request::is('calender*') ? 'is-active' : '' }}" href="{{ url('/calender') }}">Calender</a>
                    @if (Auth::guest())
                        <span class="nav-item">
                            <a class="button is-primary" href="{{ url('/login') }}">Login</a>
                        </span>
                    @else
                        <a class="nav-item is-tab {{ Request::is('watchlist*') ? 'is-active' : '' }}" href="{{ url('/watchlist') }}">Watchlist</a>
                        @if (Auth::user()->isAdmin())
                            <a class="nav-item is-tab {{ Request::is('admin*') ? 'is-active' : '' }}" href="{{ url('/admin') }}">Admin</a>
                        @endif
                        <span class="nav-item is-paddingless">
                            <a class="nav-item is-tab {{ Request::is('account*') ? 'is-active' : '' }}" href="{{ url('/account') }}">Account</a>
                            <a class="nav-item" href="{{ url('/logout') }}">Logout</a>
                        </span>
                    @endif
                </div>
            </nav>
        </div>
    </div>

    <!-- Hero content: will be in the middle -->
    <div class="hero-body" style="display:@yield('hero.display', 'initial');">
        <div class="container">
            <div class="media">
                <div class="media-left">
                    <i class="fa fa-@yield('hero.icon', 'user-secret') icon is-large"></i>
                </div>
                <div class="media-content">
                    <div class="heading">
                        <h1 class="title">@yield('hero.title', 'FMA')</h1>
                        <h2 class="subtitle">@yield('hero.subtitle', '')</h2>
                    </div>
                    <div class="content">
                        <p>@yield('hero.content')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
