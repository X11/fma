<section class="hero is-primary is-small is-left is-bold">
    <!-- Hero header: will stick at the top -->
    <div class="hero-header">
        <header class="header">
            <div class="container">
                <!-- Left side -->
                <div class="header-left">
                    <a class="header-item fma" href="{{ url('/') }}">FMA</a>
                </div>

                <!-- Hamburger menu (on mobile) -->
                <span class="header-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>

                <!-- Right side -->
                <div class="header-right header-menu">
                    @if (Auth::guest())
                        <span class="header-item">
                            <a class="button is-primary" href="{{ url('/login') }}">Login</a>
                        </span>
                    @else
                        <span class="header-item"><a class="{{ Request::is('account*') ? 'is-active' : '' }}" href="{{ url('/account') }}">Account</a></span>
                        @if (Auth::user()->isAdmin())
                            <span class="header-item"><a class="{{ Request::is('admin*') ? 'is-active' : '' }}" href="{{ url('/admin') }}">Admin</a></span>
                        @endif
                        <span class="header-item">
                            <a href="{{ url('/logout') }}">Logout</a>
                        </span>
                    @endif
                </div>
            </div>
        </header>
    </div>

    <!-- Hero content: will be in the middle -->
    <div class="hero-content">
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

    <!-- Hero footer: will stick at the bottom -->
    <div class="hero-footer">
        <nav class="tabs is-boxed">
            <div class="container">
                <ul>
                    @if (!Auth::guest())
                        <li class="{{ Request::is('home') ? 'is-active' : '' }}"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="{{ Request::is('serie*') ? 'is-active' : '' }}"><a href="{{ url('/serie') }}">Series</a></li>
                        <li class="{{ Request::is('calender*') ? 'is-active' : '' }}"><a href="{{ url('/calender') }}">Calender</a></li>
                        <li class="{{ Request::is('watchlist*') ? 'is-active' : '' }}"><a href="{{ url('/watchlist') }}">Watchlist</a></li>
                        <?php /*
                        <li class="{{ Request::is('account*') ? 'is-active' : '' }}"><a href="{{ url('/account') }}">Account</a></li>
                        <li class="{{ Request::is('admin*') ? 'is-active' : '' }}"><a href="{{ url('/admin') }}">Admin</a></li>
                        */ ?>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</section>
