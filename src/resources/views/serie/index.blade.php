@extends('layouts.app')

@section('hero.icon', 'tv')
@section('hero.title', 'Series')
@section('hero.content', 'List of all series')

@section('content')
<section class="section">
    <div class="container">
        <form role="form" method="GET" action="{{ url()->current() }}">
            <p class="control has-addons">
                <input class="input" type="text" value="{{ $query }}" name="q" id="name"/>
                <button type="submit" class="button is-primary"> Search </button>
            </p>
        </form>
    </div>
</section>
<section class="section is-paddingless series">
    <div class="container">
        @if (count($series) == 0)
            <div class="message is-danger">
                <div class="message-body">
                    Nothing to display
                </div>
            </div>
        @endif
        <div class="columns is-gapless is-multiline is-mobile">
        @foreach($series->chunk(2) as $chunk)
            @foreach ($chunk as $serie)
                <div class="column is-quarter-tablet is-half-mobile serie">
                    <a href="{{ url($serie->url) }}" class="media">
                        <img src="{{ asset('img/poster_black.png') }}" data-src="{{ $serie->poster }}" alt="" style="width:100%;"/>
                        <div class="content">
                            <h2>{{ $serie->name }}</h2>
                            <p>{{ $serie->overview }}</p>
                        </div>
<?php /*
                    <div class="media">
                        <figure class="media-image">
                            <img src="{{ $serie->poster }}" alt="" style="max-height:124px; padding-right:10px;"/>
                        </figure>
                        <div class="media-content content">
                            <h2><a href="{{ url('/serie', [$serie->id]) }}">{{ $serie->name }}</a></h2>
                            <p>{{ $serie->overview }}</p>
                        </div>
                    </div>
*/ ?>
                    </a>
                </div>
            @endforeach
        @endforeach
        </div>
    </div>   
</section>
<section class="section">
    <div class="container">
        @include('partial.pagination', ['items' => $series])
    </div>
</section>
@if ($tvdbResults)
    <section class="section">
        <div class="container">
            @if (count($tvdbResults) == 0)
                <div class="message is-danger">
                    <div class="message-body">
                        Nothing to display
                    </div>
                </div>
            @endif
            @foreach(array_chunk($tvdbResults, 2) as $chunk)
            <div class="columns">
                @foreach ($chunk as $serie)
                    <div class="column is-half has-text-centered">
                        <form class=""role="form" method="POST" action="{{ url('/serie') }}">
                            {!! csrf_field() !!}
                            <input class="input" type="hidden" value="{{ $serie->getTheTvDbId() }}" name="tvdbid" id="tvdbid"/>
                            <button type="submit" class="box banner">
                                <img src="{{ $serie->getBannerUrl() }}" alt=""/>
                                <div class="overlay"></div>
                                <p>{{ $serie->getName() }}</p>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
            @endforeach
        </div>   
    </section>
@endif
@if (!Auth::guest() && Auth::user()->isAdmin())
    <section class="section">
        <div class="container">
            <div class="heading">
                <h2 class="subtitle">New</h2>
            </div>
            <form role="form" method="POST" action="{{ url('/serie') }}">
                {!! csrf_field() !!}
                <p class="control has-addons">
                    <input class="input" type="number" value="" name="tvdbid" id="tvdbid"/>
                    <button type="submit" class="button is-primary"> Add </button>
                </p>
            </form>
        </div>
    </section>
@endif
@endsection
