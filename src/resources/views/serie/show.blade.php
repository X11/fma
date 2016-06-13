@extends('layouts.app')

@section('hero.icon', 'tv')
@section('hero.title', $serie->name)
@section('hero.content', '#'.$serie->id)

@section('content')
<section class="section serie">
    <div class="container">
        <div class="columns">
            <div class="serie-fanart column is-one-third has-text-centered">
                <figure class="has-text-centered is-hidden-mobile">
                    <img width="100%" src="{{ asset('img/poster.png') }}" data-src="{{ $serie->fanart }}" alt=""/>
                </figure>
                @if (Auth::check())
                @endif
            </div>
            <div class="column" style="order: 2;">
                <div class="content" style="max-width:600px">
                    <div class="heading">
                        <h2 class="title">{{ $serie->name }}</h2>
                        <p class="subtitle">
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
                        </p>
                    </div>
                    <br>
                    <p>
                        <small><strong>OVERVIEW:</strong></small><br>
                        {{ $serie->overview }}
                    </p>
                    <br>
                    <div class="is-clearfix">
                        <p>
                            <small><strong>GENRE:</strong></small><br>
                            @foreach($serie->genres as $genre)
                            <a href="{{ url('/serie') }}?_genre={{ $genre->id }}" class="tag is-small">{{ $genre->name }}</a>
                            @endforeach
                        </p>
                        <p class="has-text-right"><small><strong>Last updated</strong>: {{ $serie->updated_at }}</small></p>
                    </div>
                </div>
            </div>
            <div class="column is-2">
                <figure class="has-text-centered is-hidden-mobile">
                    <img data-src="{{ $serie->poster }}" alt=""/>
                </figure>
                    <button style="margin-bottom:5px;width:100%" class="button is-loading mark-serie" data-mark-initial="{{ Auth::user()->have('watching', $serie->id) ? 1 : 0 }}" data-mark-content="Track|Untrack" data-mark-class="is-primary|is-danger" data-mark-serie="{{ $serie->id }}"></button>
                @if (Auth::check() && Auth::user()->isAdmin())
                    <small class="is-hidden-tablet-only is-hidden-desktop-only is-hidden-widescreen"><strong>ADMIN:</strong></small>
                    <button style="margin-bottom:5px;width:100%" class="button is-warning" type="submit" form="updateSerie">Update</button>
                    <button style="margin-bottom:5px;width:100%" class="button is-danger" type="submit" form="deleteSerie">Delete</button>
                @endif
            </div>
        </div>
    </div>
</section>
<section class="section seasons">
    <div class="container">
        <div class="media">
            <div class="media-left">
                <i class="fa fa-circle-o-notch icon is-large"></i>
            </div>
            <div class="media-content">
                <div class="heading">
                    <h3 class="title">seasons</h3>
                </div>
            </div>
        </div>
        <br>
        <div class="tabs is-boxed is-medium" style="">
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
                                <h3>{{ $episode->name !== '' ? $episode->name : 'N/A' }}</h3>
                                <p>{{ $episode->overview ? substr($episode->overview, 0, 200) : "N/A" }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>
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
