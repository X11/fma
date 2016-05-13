@extends('layouts.app')

@section('hero.icon', 'list')
@section('hero.title', 'Watchlist')
@section('hero.content', '')

@section('content')
<section class="section">
    <div class="container">
        @if( count($series) > 0 )
        <div class="columns">
            <div class="column" style="order: 2;">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Serie</th>
                                <th>Episode</th>
                                <th>Season</th>
                                <th>Episode</th>
                                <th>Aired</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td><a href="{{ url('/serie', [$item->serie_id]) }}">{{ $item->serie_name }}</td>
                                    <td><a href="{{ url('/serie', [$item->serie_id, 'episode', $item->episode_id]) }}">{{ $item->episode_name }}</td>
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
            <div class="column is-one-quarter">
                <aside class="menu">
                    <p class="menu-label">Filters</p>
                    <ul class="menu-list">
                        <li><a href="{{ url('/watchlist') }}">None</a></li>
                        @foreach ($series as $serie)
                            <li><a href="{{ url('/watchlist') }}?_filter={{ $serie->id }}">{{ $serie->name }}</a></li>
                        @endforeach
                    </ul>
                </aside>
            </div>
        </div>
        @else
        <div class="notification is-primary">
            No series on your watchlist, Discover some on the calender!
        </div>
        @endif
    </div>
</section>
@endsection
