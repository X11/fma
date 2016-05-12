<section class="hero is-primary is-small is-bold">
    <!-- Hero header: will stick at the top -->
    <div class="hero-head">
        <div class="container">
            <nav class="nav fma">
                <!-- Left side -->
                <div class="nav-left">
                    <a class="nav-item" href="{{ url('/') }}" style="color:white !important;">FMA</a>
                </div>

                <!-- Hamburger menu (on mobile) -->
                <span class="nav-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>

                <!-- Right side -->
                <div class="nav-right nav-menu">
                    @if (Auth::guest())
                        <span class="nav-item">
                            <a class="button is-primary" href="{{ url('/login') }}">Login</a>
                        </span>
                    @else
                        <a class="nav-item {{ Request::is('account*') ? 'is-active' : '' }}" href="{{ url('/account') }}">Account</a>
                        @if (Auth::user()->isAdmin())
                            <a class="nav-item {{ Request::is('admin*') ? 'is-active' : '' }}" href="{{ url('/admin') }}">Admin</a>
                        @endif
                        <a class="nav-item" href="{{ url('/logout') }}">Logout</a>
                    @endif
                </div>
            </nav>
        </div>
    </div>

    <!-- Hero content: will be in the middle -->
    <div class="hero-body">
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
    <div class="hero-foot">
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
