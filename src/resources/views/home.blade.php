@extends('layouts.app')

@section('hero.icon', 'plane')
@section('hero.title', 'Last aired')
@section('hero.content', 'List of episodes')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-gapless">
            <div class="column">
                <p class="control heading" style="text-align:right;">
                    <label class="checkbox">
                        Hide watched
                        <input class="checkbox" type="checkbox" id="hide-watched">
                    </label>
                </p>
            </div>
            <div class="column is-10-tablet"></div>
        </div>
        <div class="home">
            @foreach($days as $day => $episodes)
                <div class="columns is-gapless">
                    <div class="column">
                        <h2 class="day-heading">{{ Carbon\Carbon::parse($day)->format('l') }}</h2>
                        <small class="day-subheading">{{ $day }}</small>
                    </div>
                    <div class="column is-10-tablet">
                        <div class="columns is-multiline is-gapless is-mobile">
                            @foreach ($episodes as $episode)
                            <div class="column is-one-quarter-desktop is-one-third-tablet is-half-mobile {{ $episode->watched ? 'is-watched' : '' }}">
                                <div class="poster">
                                    <a href="{{ url($episode->url) }}">
                                        <img width="100%" src="{{ asset('img/poster.png') }}" data-src="{{ $episode->serie->poster }}" alt=""/>
                                        <p class="subtitle">{{ $episode->season_episode }}</p>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
