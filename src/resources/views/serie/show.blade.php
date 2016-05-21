@extends('layouts.app')

@section('hero.icon', 'tv')
@section('hero.title', $serie->name)
@section('hero.content', '#'.$serie->id)

@section('content')
<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column is-one-quarter">
                <figure class="has-text-centered">
                    <img src="{{ $serie->poster }}" alt=""/>
                </figure>
            </div>
            <div class="column">
                @if (Auth::check())
                    <div class="is-pulled-right">
                        <button class="button is-loading mark-serie" data-mark-initial="{{ Auth::user()->have('watching', $serie->id) ? 1 : 0 }}" data-mark-content="Add to watchlist|Remove from watchlist" data-mark-class="is-success|is-danger" data-mark-serie="{{ $serie->id }}"></button>
                    </div>
                @endif
                <div class="content" style="max-width:700px">
                    <div class="heading">
                        <h2 class="title">{{ $serie->name }}</h2>
                        <p class="subtitle">
                            <small><strong>TVDB:</strong> {{ $serie->tvdbid }}</small>
                            @if ($serie->imdbid)
                                <br>
                                <small><strong>IMDB:</strong> {{ $serie->imdbid }}</small>
                            @endif
                            <br>
                            <small><strong>RATING:</strong> {{ $serie->rating}}/10</small>
                        </p>
                    </div>
                    <p>
                        <small><strong>OVERVIEW:</strong></small><br>
                        {{ $serie->overview }}
                    </p>
                    <div class="is-pulled-right">
                        <p>
                            @foreach($serie->genres as $genre)
                            <a href="{{ url('/serie') }}?_genre={{ $genre->id }}" class="tag is-small">{{ $genre->name }}</a>
                            @endforeach
                        </p>
                        <p class="has-text-right"><small><strong>Last updated</strong>: {{ $serie->updated_at }}</small></p>
                    </div>
                    @if (Auth::user()->isAdmin())
                        <small><strong>Admin:</strong></small><br>
                        <p class="control has-addons">
                            <button class="button is-primary is-small" type="submit" form="updateSerie">update now</button>
                            <button class="button is-danger is-small" type="submit" form="deleteSerie">delete</button>
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="seasons box">
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
            <div class="tabs is-fullwidth is-centered" style="">
                <ul>
                    @foreach ($seasons_numbers as $season)
                        <li class="{{ $season == $seasons_numbers->last() ? 'is-active' : '' }}"><a tab-href="seasons/{{$season}}">{{ $season > 0 ? $season : 'Specials' }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div id="seasons" class="tabs-content table-responsive">
                @foreach ($seasons as $season => $episodes)
                    <div tab-id="{{ $season }}" class="tab {{ $season == $seasons_numbers->last() ? 'is-active' : '' }}">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><a class="mark-season" style="color:inherit;" title="Mark all episodes as watched" data-watched-season="{{ $season }}"><i class='fa fa-check'></i></a></th>
                                    <th>Name</th>
                                    <th>Episode</th>
                                    <th>Aired</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($episodes as $episode)
                                    <tr>
                                        <td width="20px" class="watched mark-episode" data-watched-initial="{{ $episode->watched ? 1 : 0 }}" data-watched-content="<i class='fa fa-times'></i>|<i class='fa fa-check'></i>" data-watched-class="|is-watched" data-watched-episode="{{ $episode->id }}" data-watched-season="{{ $season }}"></td>
                                        <td>{{ $episode->name }}</td>
                                        <td><a href="{{ url($episode->url) }}">{{ $episode->season_episode }}</a></td>
                                        <td class="{{ $episode->isAired() ? '' : 'is-danger' }}">{{ $episode->air_date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
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
