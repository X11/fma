@extends('layouts.app')

@section('content')
<section class="section" style="overflow:hidden;">
    <div class="container">
        <label id="aside-label" for="aside-checkbox"><i class="fa fa-bars"></i> Menu</label>
        <div class="columns">
            <input type="checkbox" id="aside-checkbox"/>
            <aside class="column is-3">
                <p class="menu-label">Account</p>
                <ul class="menu-list">
                    <li><a class="{{ Request::is('*profile') ? 'is-active' : '' }}" href="{{ action('UserController@getProfile') }}">Profile</a></li>
                    <li><a class="{{ Request::is('*settings') ? 'is-active' : '' }}" href="{{ action('UserController@getSettings') }}">User Interface</a></li>
                    <li><a class="{{ Request::is('*api') ? 'is-active' : '' }}" href="{{ action('UserController@getApi') }}">API</a></li>
                    <li><a class="{{ Request::is('*security') ? 'is-active' : '' }}" href="{{ action('UserController@getSecurity') }}">Security</a></li>
                </ul>
            </aside>
            <div class="column push-content">
                @yield('main')
            </div>
        </div>
    </div>
</section>
@endsection
