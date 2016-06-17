@extends('layouts.app')

@section('hero.icon', 'tv')
@section('hero.title', $serie->name)
@section('hero.content', '#'.$serie->id)

@section('content')
@if ($serie_fanart === 'default')
    <section class="section serie-fanart">
        <img width="100%" src="{{ asset('img/poster.png') }}" data-src="{{ $serie->fanart }}" alt=""/>
    </section>
@endif
<section class="section serie">
    <div class="container">
        <div class="columns">
            <div class="column is-8">
                <div class="columns is-mobile">
                    <div class="column is-4">
                        <figure class="has-text-centered">
                            <img data-src="{{ $serie->poster }}" alt=""/>
                        </figure>
                        <button style="margin-bottom:5px;width:100%;" class="button is-loading mark-serie" data-mark-initial="{{ Auth::user()->have('watching', $serie->id) ? 1 : 0 }}" data-mark-content="Track|Untrack" data-mark-class="is-primary|is-danger" data-mark-serie="{{ $serie->id }}"></button>
                    </div>
                    <div class="column">
                        <div class="content" style="max-width:600px">
                            <div class="heading">
                                <h2 class="title">{{ $serie->name }}</h2>
                                <p class="subtitle is-marginless">
                                    <small><strong>TVDB:</strong> {{ $serie->tvdbid }}</small>
                                    @if ($serie->imdbid)
                                        <br>
                                        <small><strong>IMDB:</strong> <a href="http://www.imdb.com/title/{{ $serie->imdbid }}" target="_blank">{{ $serie->imdbid }}</a></small>
                                    @endif
                                    <br>
                                    <small><strong>RATING:</strong> {{ $serie->rating}}/10</small>
                                    @if ($serie->status)
                                        <br>
                                        <small><strong>STATUS:</strong> {{ $serie->status }}</small>
                                    @endif
                                    <br>
                                    <small><strong>GENRE{{ $serie->genres->count() > 1 ? 'S' : '' }}:</strong></small>
                                </p>
                                <ul style="margin-top:4px;">
                                    @foreach($serie->genres as $genre)
                                        <li><a href="{{ url('/serie') }}?_genre={{ $genre->id }}" class="is-link" style="color:inherit;"><small>{{ $genre->name }}</small></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="content">
                    <p>
                        <small><strong>OVERVIEW:</strong></small><br>
                        {{ $serie->overview }}
                    </p>
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <div class="is-clearfix">
                            <p class="is-pulled-right">
                                <button class="button is-link is-small" type="submit" form="updateSerie">Update</button>
                                <button class="button is-danger is-link is-small" type="submit" form="deleteSerie">Delete</button>
                            </p>
                        </div>
                    @endif
                    <div class="is-clearfix">
                        <p class="has-text-right"><small><strong>Last updated</strong>: {{ $serie->updated_at }}</small></p>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="tabs is-boxed is-centered" style="">
                    <ul>
                        @foreach ($seasons_numbers as $season)
                            <li class="{{ $season == $seasons_numbers->last() ? 'is-active' : '' }}"><a tab-href="seasons/{{$season}}">{{ $season > 0 ? $season : 'Specials' }}</a></li>
                        @endforeach
                    </ul>
                </div>
                
                <div id="seasons" class="tabs-content">
                    @foreach ($seasons as $season => $episodes)
                        <div tab-id="{{ $season }}" class="tab {{ $season == $seasons_numbers->last() ? 'is-active' : '' }}">
                            <div class="season-heading heading">
                                <span class="click-catch mark-season" data-watched-season="{{ $season }}"></span>
                                <h2 class="subtitle has-text-centered">SEASON {{ $season }}</h2>
                            </div>
                            @foreach ($episodes as $episode)
                                <div class="episode-entry {{ $episode == $episodes->last() ? 'is-last' : '' }} {{ $episode->isAired() ? 'is-aired' : 'is-not-aired' }}">
                                    <span class="click-catch mark-episode"
                                                data-watched-parent=".episode-entry"
                                                data-watched-initial="{{ $episode->watched ? 1 : 0 }}" 
                                                data-watched-class="|is-watched" 
                                                data-watched-episode="{{ $episode->id }}" 
                                                data-watched-season="{{ $season }}"></span>
                                    <div class="episode {{ $episode->isAired() ? '' : 'is-not-aired' }}">
                                        <label class="date">
                                            <span class="top">{{ str_pad($episode->episodeNumber, 2, '0', STR_PAD_LEFT) }}</span>
                                            <span class="bottom">{{ $episode->episodeSeason }}</span>
                                        </label>
                                        <a href="{{ $episode->url }}"><i class="fa fa-arrow-right"></i></a>
                                        <h3>{{ ($episode->name ? $episode->name : '') !== '' ? $episode->name : 'N/A' }}</h3>
                                        <p>{{ $episode->air_date }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@if ($serie_fanart === 'bottom')
    <section class="section serie-fanart">
        <img width="100%" src="{{ asset('img/poster.png') }}" data-src="{{ $serie->fanart }}" alt=""/>
    </section>
@endif
@endsection

@section('post-footer')
    <form id="updateSerie" action="{{ url('/serie', [$serie->id]) }}" method="POST">
        {{ method_field('PUT') }}
        {!! csrf_field() !!}
    </form>
    <form id="deleteSerie" action="{{ url('/serie', [$serie->id]) }}" method="POST">
        {{ method_field('DELETE') }}
        {!! csrf_field() !!}
    </form>
@endsection

@section('scripts')
    @if (session('refresh'))
        <script type="text/javascript" charset="utf-8">
            setTimeout(function(){location.reload()}, 5000);
        </script>
    @endif
@endsection
