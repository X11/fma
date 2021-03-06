@extends('layouts.app')

@section('description', str_limit($serie->overview, 150))
@section('title', $serie->name . ' - FMA')
@section('hero.display', 'none')

@if ($serie_fanart === 'default') 
    @section('header.after')
        <section class="section serie-fanart">
            <img width="100%" src="{{ asset('img/poster.png') }}" data-src="{{ $serie->fanart }}" alt=""/>
        </section>
    @endsection
@endif

@section('content')
<section class="section serie" triggers="initSerie">
    <div class="container">
        <div class="columns">
            <div class="column is-8">
                <div class="columns is-mobile">
                    <div class="column is-3">
                        <figure class="has-text-centered serie-poster">
                            <img src="{{ asset('img/poster.png') }}" data-src="{{ $serie->poster }}" alt=""/>
                        </figure>
                        @if (Auth::check())
                            <button style="margin-bottom:5px;width:100%;" class="button is-loading mark-serie" data-mark-initial="{{ Auth::user()->have('watching', $serie->id) ? 1 : 0 }}" data-mark-content="Track|Untrack" data-mark-class="is-primary|is-danger" data-mark-serie="{{ $serie->id }}"></button>
                        @endif
                    </div>
                    <div class="column">
                        <div class="heading">
                            <h2 class="title">{{ str_replace(['(', ')'], '', $serie->name) }} <span>{{ $serie->year }}</span></h2>
                            <p class="subtitle"><span class="icon text is-danger"><i class="fa fa-heart"></i></span> {{ $serie->rating }}% | {{ $serie->seasons }} Season{{ $serie->seasons > 1 ? 's' : '' }} | {{ $serie->status }}</p>
                        </div>
                        <br>
                        <table class="serie-info">
                            <tbody>
                                @if ($serie->imdbid)
                                    <tr><th>IMDB:</th><td><a href="http://www.imdb.com/title/{{ $serie->imdbid }}" target="_blank">{{ $serie->imdbid }}</a></td></tr>
                                @endif
                                <tr><th>TVDB:</th><td>{{ $serie->tvdbid }}</td></tr>
                                @if ($serie->tmdbid)
                                    <tr><th>TMDB:</th><td>{{ $serie->tmdbid }}</td></tr>
                                @endif
                                @if ($serie->status == "Continuing")
                                    <tr><th>NETWORK:</th><td>{{ $serie->network or 'N/A'}}</td></tr>
                                    <tr><th>AIRDAY:</th><td>{{ $serie->airday or 'N/A'}}</td></tr>
                                    <tr><th>AIRTIME:</th><td>{{ $serie->airtime or 'N/A'}}</td></tr>
                                    <tr><th>RUNTIME:</th><td>{{ $serie->runtime or 'N/A'}} minutes</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="heading">
                    <h2 class="title">OVERVIEW</h2>
                </div>
                <div class="content">
                    <p>{{ $serie->overview }}</p>
                </div>
                <br>
                @if ($serie->cast->count() > 0)
                    <br>
                    <div class="heading">
                        <h3 class="title">Cast</h3>
                    </div>
                    <div class="cast columns is-multiline is-marginless is-mobile">
                        @foreach($serie->cast as $person)
                        <div class="card column is-half-mobile is-one-third-tablet is-one-quarter-desktop is-paddingless">
                            @if ($serie_actor_images)
                            <div class="card-image">
                                <figure class="image">
                                    <img width="100%" src="{{ asset('img/poster.png') }}" @if($person->pivot->image) data-src="//thetvdb.com/banners/_cache/{{ $person->pivot->image }}" @endif alt=""/>
                                </figure>
                            </div>
                            @endif
                            <div class="card-content">
                                <h4 class="title is-5"><strong>{{ $person->name }}</strong></h4>
                                <p class="subtitle is-6">{{ $person->pivot->role }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="has-text-centered"><a href="?more={{ !$more }}">{{ $more ? 'Less' : 'More' }}</a></div>
                @endif
                @if ($serie->media->count() > 0)
                    <br>
                    <div class="heading">
                        <h3 class="title">VIDEO{{ $serie->media->count() > 1 ? 'S' : '' }}</h3>
                    </div>
                    <div class="videos columns is-multiline">
                        @foreach($serie->media as $media)
                            @if ($media->type == 'youtube')
                                <div class="column is-half">
                                    <figure class="image is-16by9">
                                        <iframe data-src="https://www.youtube.com/embed/{{ $media->source }}?modestbranding=1&controls=2" frameborder="0"></iframe>
                                        <div class="overlay" iframe-src="https://www.youtube.com/embed/{{ $media->source }}?modestbranding=1&controls=2&fs=1&autoplay=1"></div>
                                   </figure>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
                <br>
                <div class="content">
                    @if (Auth::check())
                        <div class="is-clearfix">
                            <p class="is-pulled-right">
                                <button class="button is-link is-small" type="submit" form="updateSerie">Update</button>
                                @if (Auth::user()->isAdmin())
                                <button class="button is-danger is-link is-small" type="submit" form="deleteSerie">Delete</button>
                                @endif
                            </p>
                        </div>
                    @endif
                    <div class="is-clearfix">
                        <p class="has-text-right"><small><strong>Last updated</strong>: {{ $serie->updated_at }}</small></p>
                    </div>
                </div>
            </div>
            <div class="column table-responsive">
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
                                    <a href="{{ $episode->url }}" class="episode {{ $episode->isAired() ? '' : 'is-not-aired' }}">
                                        <label class="date">
                                            <span class="top">{{ $episode->episodeSeason }}</span>
                                            <span class="bottom">{{ str_pad($episode->episodeNumber, 2, '0', STR_PAD_LEFT) }}</span>
                                        </label>
                                        <h3>{{ ($episode->name ? $episode->name : '') !== '' ? $episode->name : 'N/A' }}</h3>
                                        <p>{{ $episode->air_date or 'N/A' }}</p>
                                    </a>
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
@if ($serie_fanart !== 'never')
    <div class="modal" id="image-modal">
        <div class="modal-background"></div>
        <div class="modal-content-secondary">
            <div class="modal-prev">
                <i class="fa fa-chevron-left"></i>
            </div>
            <img src="http://placehold.it/1280x960">
            <div class="modal-next">
                <i class="fa fa-chevron-right"></i>
            </div>
        </div>
        <button class="modal-close"></button>
    </div>
@endif
<div class="modal" id="video-modal">
    <div class="modal-background"></div>
    <div class="modal-content">
        <figure class="image is-16by9">
            <iframe src="" frameborder="0"></iframe>
       </figure>
    </div>
    <button class="modal-close"></button>
</div>
@endsection

@push('post-footer')
    <form id="updateSerie" action="{{ url('/serie', [$serie->id]) }}" method="POST">
        {{ method_field('PUT') }}
        {!! csrf_field() !!}
    </form>
    <form id="deleteSerie" action="{{ url('/serie', [$serie->slug]) }}" method="POST">
        {{ method_field('DELETE') }}
        {!! csrf_field() !!}
    </form>
@endpush

@push('scripts')
    @if (session('refresh'))
        <script type="text/javascript" charset="utf-8">
            setTimeout(function(){location.reload()}, 5000);
        </script>
    @endif
@endpush
