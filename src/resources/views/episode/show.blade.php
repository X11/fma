@extends('layouts.app')

@section('hero.icon', 'tv')
@section('hero.title', $episode->season_episode)
@section('hero.content', '#'.$episode->id)

@section('content')
<section class="section">
    <div class="container">
        <div class="columns">
            <div style="order:2;" class="column is-one-quarter-tablet is-one-third">
                <figure class="has-text-centered">
                    <img src="{{ $serie->poster }}" alt=""/>
                </figure>
            </div>
            <div class="column">
                <div class="content">
                    <h2 class="title">{{ $serie->name }}<br><br><em>{{ $episode->name }}</em></h2>
                    <p>{{ $episode->overview }}</p>
                    <div class="is-clearfix">
                        <button class="button is-loading is-pulled-right mark-episode" data-watched-initial="{{ $episode->watched ? 1 : 0 }}" data-watched-content="Mark as watched|Unmark as watched" data-watched-class="is-success|is-danger" data-watched-episode="{{ $episode->id }}"></button>
                    </div>
                </div>
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
