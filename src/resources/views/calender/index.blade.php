@extends('layouts.app')

@section('hero.icon', 'calendar')
@section('hero.title', 'Calender')
@section('hero.content', 'Airdates calender')

@section('content')
<section class="section">
    <div class="container calender is-fluid">
        <div class="columns is-multiline">
            @foreach($episodes as $day => $day_episodes)
            <div class="column is-2">
                <h2>{{ $day }}</h2>
                <ul class="calender-list">
                    @foreach($day_episodes->sortBy('serie_name') as $episode)
                    <li class="calender-item {{ in_array($episode->serie->id, $watching_ids) ? 'is-watching' : ''}} {{ $episode->episodeSeason == 1 && $episode->episodeNumber == 1 ? 'is-premier' : '' }} {{ $episode->episodeNumber == 1 ? 'is-returning' : ''}}">
                        <label class="date">
                            <span class="top">{{ str_pad($episode->episodeNumber, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="bottom">{{ $episode->episodeSeason }}</span>
                        </label>
                        <h3>{{ $episode->serie->name }}</h3>
                        <p>{{ $episode->name or 'N/A' }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>   
</section>
@endsection
