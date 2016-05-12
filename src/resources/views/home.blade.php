@extends('layouts.app')

@section('hero.icon', 'plane')
@section('hero.title', 'Last aired')
@section('hero.content', 'List of episodes')

@section('content')
<section class="section">
    <div class="container">
        <nav class="level">
            <div class="level-item has-text-centered">
                <p class="heading">Hide watched</p>
                <p class="title"><input type="checkbox" id="hide-watched"></p>
            </div>
            <?php /*
            <div class="navbar-item has-text-centered">
                <p class="heading">Hide posters</p>
                <p class="title"><input type="checkbox" id="hide-posters"></p>
            </div>
            <div class="navbar-item has-text-centered">
                <p class="heading">Hide more</p>
                <p class="title"><input type="checkbox" id="hide-more"></p>
            </div>
            */ ?>
        </nav>
        <div class="home">
            @foreach($days as $day => $episodes)
                <div class="columns is-gapless">
                    <div class="column">
                        <h2><span class="tag is-primary is-large" style="justify-content:left;width:100%;border-radius: 0;">{{ $day }}</span></h2>
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
                <hr>
            @endforeach
        </div>
    </div>
</section>
@endsection
