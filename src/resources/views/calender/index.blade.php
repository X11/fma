@extends('layouts.app')

@section('title', 'Calender - FMA')
@section('hero.icon', 'calendar')
@section('hero.title', 'Calender')
@section('hero.content', 'Airdates calender')

@section('content')
<section class="section">
    <div class="container calender is-{{ $overview_container }}">
        <div class="controls heading is-clearfix">
            <p class="control">
                <label class="checkbox is-pulled-right" title="Only show Premier/Returning/Watching episodes">
                    Only show important episodes
                    <input class="checkbox" type="checkbox" id="show-important">
                </label>
            </p>
        </div>
    </div>
    <br>
    <div class="container calender is-{{ $overview_container }}">
        <div class="columns is-multiline">
            @foreach($dates as $week)
                @foreach($week as $day => $order)
                <div style="order: {{$order}};" class="column is-3 calender-column {{ $day == $today ? 'is-active' : '' }}">
                    <div class="heading">
                        <h2>{{ Carbon\Carbon::parse($day)->format('D, j M') }}</h2>
                    </div>
                    <ul class="calender-list">
                        @foreach($episodes->get($day, collect([]))->sortBy('serie_name') as $episode)
                        <li class="calender-item {{ in_array($episode->serie->id, $watching_ids) ? 'is-watching' : ''}} {{ $episode->episodeSeason == 1 && $episode->episodeNumber == 1 ? 'is-premier' : '' }} {{ $episode->episodeNumber == 1 ? 'is-returning' : ''}}">
                            <a href="{{ $episode->url }}">
                                <label class="date">
                                    <span class="top">{{ $episode->episodeSeason }}</span>
                                    <span class="bottom">{{ str_pad($episode->episodeNumber, 2, '0', STR_PAD_LEFT) }}</span>
                                </label>
                                <h3>{{ $episode->serie->name }}</h3>
                                <p>{{ $episode->name or 'N/A' }}</p>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            @endforeach
        </div>
    </div>
</section>
@endsection
