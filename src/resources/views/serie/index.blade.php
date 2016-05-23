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
<section class="section is-paddingless series" style="overflow:hidden;">
    <div class="container">
        <label id="aside-label" for="aside-checkbox"><i class="fa fa-bars"></i> Options</label>
        <div class="columns is-mobile">
            <input type="checkbox" id="aside-checkbox"/>
            <aside class="column is-2">
                <p class="menu-label">Sort</p>
                <ul class="menu-list">
                    <li><a href="{{ action('SerieController@index') }}?_sort=popular">Popularity</a></li>
                    <li><a href="{{ action('SerieController@index') }}?_sort=rating">Rating</a></li>
                    <li><a href="{{ action('SerieController@index') }}?_sort=recent">Recently added</a></li>
                    <li><a href="{{ action('SerieController@index') }}?_sort=upcoming">Upcoming</a></li>
                </ul>
                <p class="menu-label">Genres</p>
                <ul class="menu-list">
                    @foreach($genres as $genre)
                        <li><a href="{{ action('SerieController@index') }}?_genre={{ $genre->id }}">{{ $genre->name }}</a></li>
                    @endforeach
                </ul>
            </aside>
            <div class="column push-content">
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
                            <div class="column is-one-third-desktop is-half-tablet is-12-mobile serie">
                                <a href="{{ url($serie->url) }}" class="media">
                                    <img src="{{ asset('img/fanart.png') }}" data-src="{{ $serie->fanart or asset('img/fanart.png')}}" alt="" style="width:100%;"/>
                                    <h3 class="subtitle">{{ $serie->name }}</h3>
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                </div>
                @include('partial.pagination', ['items' => $series])
                <hr>
                @if ($tvdbResults)
                    <div class="content">
                        <p>Serie not in the above results? Find it here to add it to the system</p>
                    </div>
                    @foreach($tvdbResults->chunk(2) as $chunk)
                    <div class="columns">
                        @foreach ($chunk as $serie)
                            <div class="column is-half has-text-centered">
                                <form class=""role="form" method="POST" action="{{ url('/serie') }}">
                                    {!! csrf_field() !!}
                                    <input class="input" type="hidden" value="{{ $serie->getId() }}" name="tvdbid" id="tvdbid"/>
                                    <button style="margin:auto;" type="submit" class="box banner {{ $serie->getBanner() != "" ? '' : 'no-banner' }}">
                                        @if($serie->getBanner() != "")
                                        <img data-src="http://thetvdb.com/banners/_cache/{{ $serie->getBanner() }}" alt=""/>
                                        @endif
                                        <div class="overlay"></div>
                                        <p>{{ $serie->getSeriesName() }}</p>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                    @endforeach
                @endif
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
