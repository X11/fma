@extends('layouts.app')

@section('hero.icon', 'user')
@section('hero.title', $profile->name)
@section('hero.content', $profile->role)

@section('content')
<?php /*
    @if (Auth::user()->isAdmin() && $profile->role_index < Auth::user()->role_index)
        <form role="form" method="POST" action="{{ url('/admin/user', [$profile->id, 'role']) }}">
            {!! csrf_field() !!}
            <label class="label">User role</label>
            <p class="control has-addons">
                <span class="select">
                    <select name="role">
                        @foreach ($profile->USER_ROLES as $i => $role)
                            <option {{ $i < Auth::user()->role_index ? '' : 'disabled' }} {{ $role == $profile->role ? 'selected' : '' }} value="{{$i}}">{{ $role }}</option>
                        @endforeach
                    </select>
                </span>
                <button class="button is-primary" type="submit"><span class="icon"><i class="fa fa-edit"></i></span></button>
            </p>
        </form>
    @endif
 */ ?>
<section class="section profile">
    <div class="container">
        <div class="columns is-vcentered">
            <div class="column is-two-thirds">
                <div class="columns is-vbottom">
                    <div class="column is-narrow profile-image">
                        <img src="http://www.gravatar.com/avatar/{{ md5(strtolower(trim($profile->email))) }}?s=300&default=mm" alt="Image">
                    </div>
                    <div class="column">
                        <div class="heading">
                            <h1 class="title">{{ $profile->name }}</h1>
                            <p class="subtitle tag {{ $role_tags[$profile->role] }}">{{ $profile->role }}</p>
                        </div>
                        <table class="profile-info">
                            <tbody>
                                @if (Auth::user()->isModerator())
                                    <tr><th>Email:</th><td>{{ $profile->email}}</td></tr>
                                @endif
                                <tr><th>Last login:</th><td>{{ $profile->last_login->diffForHumans()}}</td></tr>
                                <tr><th>Account since:</th><td>{{ $profile->created_at ? $profile->created_at->toDateString() : 'N/A' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="column is-one-third">
                <div class="columns is-mobile">
                    <div class="column has-text-centered">
                        <p class="heading">Tracking</p>
                        <p class="title">{{ $profile->watching()->count() }}</p>
                    </div>
                    <div class="column  has-text-centered">
                        <p class="heading">Watched</p>
                        <p class="title">{{ $profile->watched()->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section profile">
    <div class="container">
        <div class="columns">
            <div class="column" style="order: 2;">
                <div class="heading">
                    <h2 class="title">Activity feed</h2>
                </div>
                <br>
                <ul class="feeds">
                    @foreach($logs as $log)
                    <li class="feed-entry is-empty {{ $log == $logs->last() ? 'is-last' : '' }}">
                        <div class="feed">
                            <h4>{{ $log->humanize() }}</h4>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="column is-half-tablet is-one-third-desktop">
                <div class="heading">
                    <h2 class="title">Track list</h2>
                </div>
                <br>
                @foreach ($series as $serie)
                    <div class="serie box is-paddingless">
                        <div class="columns is-gapless is-mobile">
                            <div class="column is-narrow">
                                <img width="100%" src="{{ asset('img/poster.png') }}"  data-src="{{ $serie->poster }}" alt=""/>
                            </div>
                            <div class="column">
                                <div class="content">
                                    <h2><a href="{{ url('/serie', [$serie->slug]) }}">{{ $serie->name }}</a></h2>
                                    <p>{{ str_limit($serie->overview, 180) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @include('partial.pagination', ['items' => $series])
            </div>
        </div>
    </div>
</section>
@endsection
