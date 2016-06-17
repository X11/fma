@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container">
        <label id="aside-label" for="aside-checkbox"><i class="fa fa-bars"></i> Menu</label>
        <div class="columns is-mobile">
            <input type="checkbox" id="aside-checkbox"/>
            <aside class="column is-3">
                <p class="menu-label">Admin</p>
                <ul class="menu-list">
                    <li><a class="{{ Request::is('*stats') ? 'is-active' : '' }}" href="{{ action('AdminController@stats') }}">Stats</a></li>
                    <li><a class="{{ Request::is('*users') ? 'is-active' : '' }}" href="{{ action('AdminController@users') }}">Users</a></li>
                    <li><a class="{{ Request::is('*update') ? 'is-active' : '' }}" href="{{ action('AdminController@update') }}">Update</a></li>
                    <li><a class="{{ Request::is('*cache') ? 'is-active' : '' }}" href="{{ action('AdminController@cache') }}">Cache</a></li>
                </ul>
            </aside>
            <div class="column push-content">
                @yield('main')
            </div>
        </div>
    </div>
</section>
@endsection
