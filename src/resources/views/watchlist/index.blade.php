@extends('layouts.app')

@section('hero.icon', 'list')
@section('hero.title', 'Watchlist')
@section('hero.content', '')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column" style="order: 2;">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Season</th>
                                <th>Episode</th>
                                <th>Aired</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td><a href="{{ url('/serie', [$item->serie_id, 'episode', $item->episode_id]) }}">{{ $item->serie_name }}</td>
                                    <td>{{ $item->episode_season }}</td>
                                    <td>{{ $item->episode_number }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->episode_aired)->toDateString() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @include('partial.pagination', ['items' => $items])
                </div>
            </div>
            <div class="column is-quarter">
                <aside class="menu">
                    <p class="menu-label">Series</p>
                    <ul class="menu-list">
                    @foreach ($series as $id => $serie)
                        <li><a href="{{ url('/serie', [$serie->id]) }}">{{ $serie->name }}</a></li>
                    @endforeach
                    </ul>
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection
