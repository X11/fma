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
                    <div class="panel-block">
                        <p class="control">
                            <input id="watchlist_filter_search" class="input" type="text" placeholder="Search">
                            <style id="watchlist_filter_search_style"></style>
                        </p>
                    </div>
                    @foreach ($series as $serie)
                        <label class="panel-block" watchlist-serie="{{ strtolower($serie->name) }}">
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
                <form role="form" method="GET" action="{{ action('WatchlistController@index') }}">
                    <p class="control has-addons">
                        <input class="input" type="text" value="{{ $query }}" name="q" id="name"/>
                        <button type="submit" class="button is-primary"><i class="fa fa-search"></i></button>
                    </p>
                </form>
                <ul class="link-list">
                    @foreach ($items as $item)
                    <li class="item">
                        <a href="{{ url('/serie', [$item->serie_slug, 'episode', $item->episode_id]) }}">
                            <label class="date">
                                <span class="top">{{ $item->episode_season }}</span>
                                <span class="bottom">{{ str_pad($item->episode_number, 2, '0', STR_PAD_LEFT) }}</span>
                            </label>
                            <h3>{{ $item->serie_name }} <small>{{ $item->episode_name }}</small></h3>
                            <?php $date = Carbon\Carbon::parse($item->episode_aired) ?>
                            <p>{{ $date->toDateString() }} - {{ $date->diffForHumans() }}</p>
                        </a>
                    </li>
                    @endforeach
                </ul>
                @include('partial.pagination', ['items' => $items])
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

@section('post-footer')
    <script>window.VIEW = "watchlist";</script>
@endsection
