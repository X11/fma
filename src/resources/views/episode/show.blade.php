@extends('layouts.app')

@section('hero.icon', 'tv')
@section('hero.title', $episode->name)
@section('hero.content', '#'.$episode->id)

@section('content')
<section class="section episode">
    <div class="container">
        <div class="columns">
            <div class="column">
                @if (Auth::user()->isAdmin())
                <div class="is-pulled-right">
                    <p class="control has-addons">
                        <button class="button is-danger is-small" type="submit" form="deleteEpisode">Delete</button>
                    </p>
                </div>
                @endif
                <div class="heading">
                    <h2 class="title">{{ $episode->name }}</h2>
                    <p class="subtitle"><em>{{ $serie->name }}</em></p>
                </div>
                <div class="content">
                    <p>{{ $episode->overview }}</p>
                </div>
                <div class="is-clearfix">
                    @if ($prevEpisode)
                        <a class="button is-primary" href="{{ $prevEpisode->url }}">{{ $prevEpisode->seasonEpisode }}</a>
                    @endif
                    @if ($nextEpisode)
                        <a class="button is-primary is-pulled-right" href="{{ $nextEpisode->url }}">{{ $nextEpisode->seasonEpisode }}</a>
                    @endif
                </div>
            </div>
            <div class="serie-poster column is-one-quarter-tablet is-one-third">
                <figure class="has-text-centered is-hidden-mobile">
                    <img data-src="{{ $serie->poster }}" alt=""/>
                </figure>
                <button class="button is-loading mark-episode" data-watched-initial="{{ $episode->watched ? 1 : 0 }}" data-watched-content="Mark as watched|Unmark as watched" data-watched-class="is-success|is-danger" data-watched-episode="{{ $episode->id }}"></button>
            </div>
        </div>
        @if (count($magnets) > 0)
        <hr>
        <div class="magnets box">
            <div class="heading">
                <h3 class="subtitle">Magnets</h3>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Seeds</th>
                            <th>Peers</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($magnets as $magnet)
                            <tr>
                                <td><a href="{{ $magnet->getMagnet() }}">{{ $magnet->getName() }}</a></td>
                                <td>{{ $magnet->getSize() }}</td>
                                <td>{{ $magnet->getSeeds() }}</td>
                                <td>{{ $magnet->getPeers() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@section('post-footer')
    <form id="deleteEpisode" action="{{ url('/episode', [$episode->id]) }}" method="POST">
        {{ method_field('DELETE') }}
        {!! csrf_field() !!}
    </form>
@endsection
