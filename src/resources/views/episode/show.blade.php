@extends('layouts.app')

@section('hero.icon', 'tv')
@section('hero.title', $serie->name . ' ' . $episode->season_episode)
@section('hero.content', '#'.$episode->id)

@section('content')
<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column is-quarter">
                <figure>
                    <img src="{{ $serie->poster }}" alt=""/>
                </figure>
            </div>
            <div class="column">
                <div class="content box">
                    <h2 class="title">{{ $episode->name }}</h2>
                    <p>{{ $episode->overview }}</p>
                    <div class="is-clearfix">
                        <button class="button is-loading is-pulled-right mark-episode" data-watched-initial="{{ $episode->watched ? 1 : 0 }}" data-watched-content="Mark as watched|Unmark as watched" data-watched-class="is-success|is-danger" data-watched-episode="{{ $episode->id }}"></button>
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
        </div>
    </div>
</section>
@endsection