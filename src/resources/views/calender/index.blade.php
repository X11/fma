@extends('layouts.app')

@section('hero.icon', 'calendar')
@section('hero.title', 'Calender')
@section('hero.content', 'Airdates calender')

@section('content')
<section class="section">
    <div class="container calender is-fluid">
        @foreach($dates as $day)
        <div class="columns ">
            @foreach($day as $date)
            <div class="column calender-column {{ $date == $today ? 'is-active' : '' }}">
                <div class="heading">
                    <h2>{{ Carbon\Carbon::parse($date)->format('D, j M') }}</h2>
                </div>
                <ul class="calender-list">
                    @foreach($episodes->get($date, collect([]))->sortBy('serie_name') as $episode)
                    <li class="calender-item {{ in_array($episode->serie->id, $watching_ids) ? 'is-watching' : ''}} {{ $episode->episodeSeason == 1 && $episode->episodeNumber == 1 ? 'is-premier' : '' }} {{ $episode->episodeNumber == 1 ? 'is-returning' : ''}}">
                        <a href="{{ $episode->url }}">
                            <label class="date">
                                <span class="top">{{ str_pad($episode->episodeNumber, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="bottom">{{ $episode->episodeSeason }}</span>
                            </label>
                            <h3>{{ $episode->serie->name }}</h3>
                            <p>{{ $episode->name or 'N/A' }}</p>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</section>
@endsection
