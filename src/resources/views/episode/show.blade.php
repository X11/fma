@extends('layouts.app')

@section('title', $serie->name.' '.$episode->name.' - FMA')
@section('hero.icon', 'tv')
@section('hero.title', $episode->name)
@section('hero.content', '#'.$episode->id)

@section('content')
<section class="section episode">
    <div class="container">
        <div class="is-clearfix">
            @if ($prevEpisode)
                <a class="" href="{{ $prevEpisode->url }}"><i class="fa fa-angle-double-left"></i> {{ $prevEpisode->seasonEpisode }}</a>
            @endif
            @if ($nextEpisode)
                <a class="is-pulled-right" href="{{ $nextEpisode->url }}">{{ $nextEpisode->seasonEpisode }} <i class="fa fa-angle-double-right"></i></a>
            @endif
        </div>
        <br>
        <div class="columns">
            <div class="serie-poster column is-one-quarter-tablet is-one-third" style="order: 2;">
                <figure class="has-text-centered is-hidden-mobile">
                    <img data-src="{{ $serie->poster }}" alt=""/>
                </figure>
                @if (Auth::check())
                    <button class="button is-loading mark-episode" data-watched-initial="{{ $episode->watched ? 1 : 0 }}" data-watched-content="Mark as watched|Unmark as watched" data-watched-class="is-success|is-danger" data-watched-episode="{{ $episode->id }}"></button>
                @endif
                <figure class="has-text-centered is-hidden-mobile">
                    <img data-src="{{ $episode->image }}" alt=""/>
                </figure>
            </div>
            <div class="column">
                <div class="heading">
                    <h2 class="title">{{ $episode->name }}</h2>
                    <p class="subtitle"><em>{{ $serie->name }}</em></p>
                </div>
                <div class="content">
                    <table class="serie-info">
                        <tbody>
                            @if ($episode->imdbid)
                                <tr><th>IMDB:</th><td><a href="http://www.imdb.com/title/{{ $episode->imdbid }}" target="_blank">{{ $episode->imdbid }}</a></td></tr>
                            @endif
                            <tr><th>RATING:</th><td>{{ $episode->rating or '-' }}/10</td></tr>
                            <tr><th>RUNTIME:</th><td>{{ $serie->runtime or 'N/A'}} minutes</td></tr>
                            <tr><th>TVDB:</th><td>{{ $episode->episodeid }}</td></tr>
                        </tbody>
                    </table>
                    <br>
                    <p>{{ $episode->overview }}</p>
                    @if ($episode->guests->count() > 0)
                        <h3>Guests</h3>
                        <ul>
                            @foreach($episode->guests as $person)
                            <li>{{ $person->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if ($episode->directors->count() > 0)
                        <h3>Directors</h3>
                        <ul>
                            @foreach($episode->directors as $person)
                            <li>{{ $person->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if ($episode->writers->count() > 0)
                        <h3>Writers</h3>
                        <ul>
                            @foreach($episode->writers as $person)
                            <li>{{ $person->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="content">
                    @if (Auth::check())
                        <div class="is-clearfix">
                            <p class="is-pulled-right">
                                @if (Auth::user()->isModerator())
                                    <a class="button is-link is-small" href="?_t={{ base64_encode(time()) }}">Share</a>
                                @endif
                                <button class="button is-link is-small" type="submit" form="updateEpisode">Update</button>
                                @if (Auth::user()->isAdmin())
                                <button class="button is-danger is-link is-small" type="submit" form="deleteEpisode">Delete</button>
                                @endif
                            </p>
                        </div>
                    @endif
                    <div class="is-clearfix">
                        <p class="has-text-right"><small><strong>Last updated</strong>: {{ $serie->updated_at }}</small></p>
                    </div>
                </div>
            </div>
        </div>
        @if ((Auth::check() && Auth::user()->isMember()) || $validToken)
        <hr>
        <div class="columns" triggers="initSources">
            <div class="column is-6">
                <div class="heading">
                    <h3><span class="icon"><i class="fa fa-magnet"></i></span> Magnets</h3>
                </div>
                <ul class="link-list" id="magnetList">
                </ul>
            </div>
            <div class="column is-6">
                <div class="heading">
                    <h3><span class="icon"><i class="fa fa-youtube-play"></i></span> Sources</h3>
                </div>
                <div class="content">
                    <p>Stream {{ $serie->name }} directly by clicking on one of the links below</p>
                </div>
                <div class="link-frame image is-16by9 is-hidden">
                    <iframe allowfullscreen width="100%" src="" frameborder="0"></iframe>
                </div>
                <ul class="link-list" id="linkList">
                </ul>
            </div>
        </div>
        <div class="sources-loading has-text-centered"><i class="fa fa-refresh fa-spin fa-3x fa-fw" aria-hidden="true"></i></div>              
        @endif
    </div>
</section>
@endsection

@push('post-footer')
    <form id="updateEpisode" action="{{ url('/episode', [$episode->id]) }}" method="POST">
        {{ method_field('PUT') }}
        {!! csrf_field() !!}
    </form>
    <form id="deleteEpisode" action="{{ url('/episode', [$episode->id]) }}" method="POST">
        {{ method_field('DELETE') }}
        {!! csrf_field() !!}
    </form>
    @if ((Auth::check() && Auth::user()->isMember()) || $validToken)
        <script id="magnetTemplate" type="text/template">
            <li class="item magnet">
                <a href="__magnet__" title="__name__">
                    <label class="date fixed">
                        <span class="bottom text is-success">__seeds__</span>
                        <span class="top text is-danger">__peers__</span>
                    </label>
                    <h3>__size__</h3>
                    <p>__name__</p>
                </a>
            </li>
        </script>
        <script id="linkTemplate" type="text/template">
            <li class="item source">
                <div>
                    <h3>__key__</h3>
                    <div class="content">
                        <ul>
                            __list__
                        </ul>
                    </div>
                </div>
            </li>
        </script>
        <script id="linkItemTemplate" type="text/template">
            <li><a class="link-item" href="__href__" target="_blank">__path__ <span style="vertical-align:bottom;" class="is-small icon"><i class="fa fa-external-link"></i></span></a></li>
        </script>
    @endif
@endpush

@push('scripts')
    @if ($refresh)
        <script type="text/javascript" charset="utf-8">
            setTimeout(function(){location.reload()}, 5000);
        </script>
    @endif
    <script type="text/javascript" charset="utf-8">
        window.episodeId = {{$episode->id}}
    </script>
@endpush
