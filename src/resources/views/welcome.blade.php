@extends('layouts.app')

@section('content')
<section class="hero is-left is-small is-bold">
    <div class="hero-body">
        <div class="container">
            <h1 class="title">FMA</h1>
            <p class="subtitle">Feeding my addiction</p>
        </div>
    </div>
</section>
<section class="section is-small">
    <div class="container">
        <form role="form" method="POST" action="{{ url('/login') }}">
            {!! csrf_field() !!}
            <div class="columns">
                <div class="column">
                    <p class="control has-icon">
                        <input class="input" type="email" placeholder="Email" value="{{ old('email') }}" name="email" id="email">
                        <i class="fa fa-envelope"></i>
                    </p>
                </div>
                <div class="column">
                    <p class="control has-icon">
                        <input class="input" type="password" placeholder="Password" name="password" value="" id="password">
                        <i class="fa fa-lock"></i>
                    </p>
                </div>
                <div class="column is-narrow">
                    <p class="control">
                        <button class="button is-success">Login</button>
                    </p>
                </div>
            </div>
        </form>
    </div>
</section>
<section style="background-image: url({{ $fanart }});" class="section is-paddingless landing-info">
    <div class="container">
        <div class="info">
            <div class="heading">
                <h2 class="title">Keeping track</h2>
                <p>Never forget what you are watching</p>
            </div>
        </div>
    </div>
</section>
<?php /*
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
