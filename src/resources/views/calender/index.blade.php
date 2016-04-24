@extends('layouts.app')

@section('hero.icon', 'calendar')
@section('hero.title', 'Calender')
@section('hero.content', 'Airdates calender')

@section('content')
<section class="section">
    <div class="container calender is-fluid">
        @foreach($episode_chunks as $episodes)
            <div class="columns">
                @foreach($episodes as $day => $day_episodes)
                <div class="column">
                    <h2 id="{{ $day }}" class="tag is-primary {{ $day == $today ? 'is-danger' : '' }}" style="border-radius:0;"><a href="#{{$day}}" style="color:inherit;">{{ Carbon\Carbon::parse($day)->format('D, d M') }}</a></h2>
                    <table class="table">
                        <tbody>
                            @if (count($day_episodes) > 0)
                                @foreach($day_episodes->sortBy('serie_name') as $episode)
                                <tr>
                                    <td class="table-link {{ in_array($episode->serie->id, $watching_ids) ? 'is-watching' : ''}} {{ $episode->episodeSeason == 1 ? 'is-first-season' : '' }} {{ $episode->episodeNumber == 1 ? 'is-first-episode' : ''}}">
                                        <a href="{{ url($episode->url) }}">{{ $episode->serie->name }} {{ $episode->season_episode }} </a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>N/A</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
        @endforeach
    </div>   
</section>
@endsection
