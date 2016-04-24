@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-mobile is-multiline">
            <div class="column is-half-mobile navbar-item has-text-centered">
                <p class="title" data-counter-time="1000" data-counter="{{ $seriesCount }}">{{ $seriesCount }}</p>
                <p class="heading">Series</p>
            </div>
            <div class="column is-half-mobile navbar-item has-text-centered">
                <p class="title" data-counter-time="1000" data-counter="{{ $followingCount }}">{{ $followingCount }}</p>
                <p class="heading">Series followed</p>
            </div>
            <div class="column is-half-mobile navbar-item has-text-centered">
                <p class="title" data-counter-time="1000" data-counter="{{ $episodesCount }}">{{ $episodesCount }}</p>
                <p class="heading">Episodes</p>
            </div>
            <div class="column is-half-mobile navbar-item has-text-centered">
                <p class="title" data-counter-time="1000" data-counter="{{ $watchedCount }}">{{ $watchedCount }}</p>
                <p class="heading">Episodes Watched</p>
            </div>
        </div>
    </div>
</section>
<section class="hero is-primary is-left is-medium is-bold">
    <div class="hero-content">
        <div class="container">
            <h1 class="title">FMA</h1>
            <p class="subtitle">Feeding my addiction</p>
        </div>
    </div>
    <div class="hero-footer">
        <nav class="tabs is-boxed">
            <div class="container">
                <ul>
                    <li class="is-active"><a href="{{ url('/login') }}">Login</a></li>
                    @if (env('ALLOW_REGISTER'))
                    <li><a href="{{ url('/register') }}">Register</a></li>
                    @endif (env('ALLOW_REGISTER'))
                </ul>
            </div>
        </nav>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="heading">
            <h2 class="title">Login</h2>
        </div>
        <hr>
        <form role="form" method="POST" action="{{ url('/login') }}">
            {!! csrf_field() !!}
            <div class="columns">
                <div class="column">
                    <p class="control">
                        <label for="email">Email</label>
                        <input class="input" type="email" value="{{ old('email') }}" name="email" id="email"/>
                    </p>
                </div>
                <div class="column">
                    <p class="control">
                        <label for="password">Password</label>
                        <input class="input" type="password" name="password" value="" id="password">
                    </p>
                </div>
            </div>
            <p class="controler">
                <label class="checkbox">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
            </p>
            <div class="is-clearfix">
                <button type="submit" class="button is-primary is-pulled-right">Login</button>
                <a class="button is-link is-pulled-right" href="{{ url('/password/reset') }}"><small>Forgot Your Password?</small></a>
            </div>
        </form>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="columns is-mobile">
            <div class="column has-text-centered">
                <a class="button is-outlined" href="{{ url('/serie') }}">DISCOVER</a>
            </div>
            <div class="column has-text-centered">
                <a class="button is-primary is-medium" href="{{ url('/calender') }}">CALENDER</a>
            </div>
            <div class="column has-text-centered">
                <a class="button is-outlined" href="{{ url('/watchlist') }}">WATCHLIST</a>
            </div>
        </div>
    </div>
</section>
<?php /*
<section class="section is-paddingless">
    <div class="container-fluid">
        <div class="columns is-multiline is-gapless is-mobile">
            @foreach($randomSeries as $serie)
            <div class="column is-2">
                <div class="poster">
                    <img src="{{ $serie->poster }}" alt=""/>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
 */ ?>
@endsection
