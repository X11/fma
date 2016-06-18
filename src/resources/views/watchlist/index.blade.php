@extends('layouts.app')

@section('hero.icon', 'list')
@section('hero.title', 'Watchlist')
@section('hero.content', '')

@section('content')
<section class="section">
    <div class="container">
        @if( count($series) > 0 )
        <label id="aside-label" for="aside-checkbox"><i class="fa fa-bars"></i> Filters</label>
        <div class="columns is-mobile">
            <input type="checkbox" id="aside-checkbox"/>
            <aside class="column is-6-mobile is-3-tablet">
                <nav class="panel">
                    <p class="panel-heading">Filters</p>
                    @foreach ($series as $serie)
                        <label class="panel-block">
                            <span class="tag {{ $series_episode_count->get($serie->id, 0) > 0 ? '' : 'is-dark' }} is-small">{{ str_pad($series_episode_count->get($serie->id, '0'), 2, '0', STR_PAD_LEFT) }}</span> 
                            <input type="checkbox" watchlist-filter="{{ $serie->id }}" {{ in_array($serie->id, $filters->toArray()) ? '' : 'checked' }}>
                            <a href="{{ $serie->url }}">{{ $serie->name }}</a>
                        </label>
                    @endforeach
                    <div class="panel-block">
                        <button watchlist-reset-filters class="button is-danger is-outlined is-fullwidth">
                            Reset filters
                        </button>
                    </div>
                </nav>
            </aside>
            <div class="column push-content push-6">
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
        </div>
        @else
        <div class="notification is-primary">
            No series on your watchlist, Discover some on the calender!
        </div>
        @endif
    </div>
</section>
@endsection
