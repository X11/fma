@extends('layouts.app')

@section('hero.icon', 'tv')
@section('hero.title', 'Series')
@section('hero.content', 'List of all series')

@section('content')
<section class="section">
    <div class="container">
        <form role="form" method="GET" action="{{ action('SerieController@index') }}">
            <p class="control has-addons">
                <input class="input" type="text" value="{{ $query }}" name="q" id="name"/>
                <button type="submit" class="button is-primary"> Search </button>
            </p>
        </form>
    </div>
</section>
<section class="section is-paddingless series">
    <div class="container">
        <div class="columns">
            <div class="column is-2">
                <aside>
                    <p class="menu-label">Filters</p>
                    <ul class="menu-list">
                        <li><a href="">Popular</a></li>
                        <li><a href="">Recently added</a></li>
                        <li><a href="">Airing soon</a></li>
                    </ul>
                    <p class="menu-label">Genres</p>
                    <ul class="menu-list">
                        @foreach($genres as $genre)
                            <li><a href="{{ url('/serie') }}?_genre={{ $genre->id }}">{{ $genre->name }}</a></li>
                        @endforeach
                    </ul>
                </aside>
            </div>
            <div class="column">
                @if (count($series) == 0)
                    <div class="message is-danger">
                        <div class="message-body">
                            Nothing to display
                        </div>
                    </div>
                @endif
                <div class="columns is-gapless is-multiline is-mobile">
                    @foreach($series->chunk(2) as $chunk)
                        @foreach ($chunk as $serie)
                            <div class="column is-one-quarter-tablet is-half-mobile serie">
                                <a href="{{ url($serie->url) }}" class="media">
                                    <img src="{{ asset('img/poster_black.png') }}" data-src="{{ $serie->poster }}" alt="" style="width:100%;"/>
                                    <div class="content">
                                        <h2>{{ $serie->name }}</h2>
                                        <p>{{ $serie->overview }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                </div>
                @include('partial.pagination', ['items' => $series])
            </div>
        </div>
    </div>   
</section>
@if ($tvdbResults)
    <section class="section">
        <div class="container">
            @if (count($tvdbResults) == 0)
                <div class="message is-danger">
                    <div class="message-body">
                        Nothing to display
                    </div>
                </div>
            @endif
            @foreach($tvdbResults->chunk(2) as $chunk)
            <div class="columns">
                @foreach ($chunk as $serie)
                    <div class="column is-half has-text-centered">
                        <form class=""role="form" method="POST" action="{{ url('/serie') }}">
                            {!! csrf_field() !!}
                            <input class="input" type="hidden" value="{{ $serie->getId() }}" name="tvdbid" id="tvdbid"/>
                            <button style="margin:auto;" type="submit" class="box banner">
                                <img src="http://thetvdb.com/banners/_cache/{{ $serie->getBanner() }}" alt=""/>
                                <div class="overlay"></div>
                                <p>{{ $serie->getSeriesName() }}</p>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
            @endforeach
        </div>   
    </section>
@endif
@if (!Auth::guest() && Auth::user()->isAdmin())
    <section class="section">
        <div class="container">
            <div class="heading">
                <h2 class="subtitle">New</h2>
            </div>
            <form role="form" method="POST" action="{{ url('/serie') }}">
                {!! csrf_field() !!}
                <p class="control has-addons">
                    <input class="input" type="number" value="" name="tvdbid" id="tvdbid"/>
                    <button type="submit" class="button is-primary"> Add </button>
                </p>
            </form>
        </div>
    </section>
@endif
@endsection
