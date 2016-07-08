@extends('layouts.app')

@section('hero.icon', 'tv')
@section('hero.title', $episode->name)
@section('hero.content', '#'.$episode->id)

@section('content')
<section class="section episode">
    <div class="container">
        <div class="columns">
            <div class="column">
                @if (Auth::user()->isAdmin())
                <div class="is-pulled-right">
                    <p class="control has-addons">
                        <button class="button is-danger is-small" type="submit" form="deleteEpisode">Delete</button>
                    </p>
                </div>
                @endif
                <div class="heading">
                    <h2 class="title">{{ $episode->name }}</h2>
                    <p class="subtitle"><em>{{ $serie->name }}</em></p>
                </div>
                <div class="content">
                    <p>{{ $episode->overview }}</p>
                </div>
                <div class="is-clearfix">
                    @if ($prevEpisode)
                        <a class="button is-primary" href="{{ $prevEpisode->url }}">{{ $prevEpisode->seasonEpisode }}</a>
                    @endif
                    @if ($nextEpisode)
                        <a class="button is-primary is-pulled-right" href="{{ $nextEpisode->url }}">{{ $nextEpisode->seasonEpisode }}</a>
                    @endif
                </div>
            </div>
            <div class="serie-poster column is-one-quarter-tablet is-one-third">
                <figure class="has-text-centered is-hidden-mobile">
                    <img data-src="{{ $serie->poster }}" alt=""/>
                </figure>
                <button class="button is-loading mark-episode" data-watched-initial="{{ $episode->watched ? 1 : 0 }}" data-watched-content="Mark as watched|Unmark as watched" data-watched-class="is-success|is-danger" data-watched-episode="{{ $episode->id }}"></button>
            </div>
        </div>
        <hr>
        <div class="columns">
            @if (count($magnets) > 0)
            <div class="column is-6">
                <div class="heading">
                    <h3><span class="icon"><i class="fa fa-magnet"></i></span> Magnets</h3>
                </div>
                <ul class="link-list">
                    @foreach ($magnets as $magnet)
                    <li class="item">
                        <a href="{{ $magnet->getMagnet() }}" title="{{ $magnet->getName() }}">
                            <label class="date fixed">
                                <span class="bottom text is-success">{{ $magnet->getSeeds() }}</span>
                                <span class="top text is-danger">{{ $magnet->getPeers() }}</span>
                            </label>
                            <h3>{{ $magnet->getSize() }}</h3>
                            <p>{{ $magnet->getName() }}</p>
                        </a>
                    </li>
                    @endforeach
                    <p class="has-text-right">Magnets from <a href="https://kat.cr/" target="_blank">KAT</a></p>
                </ul>
            </div>
            @endif
            @if (count($links) > 0)
            <div class="column is-6">
                <div class="heading">
                    <h3><span class="icon"><i class="fa fa-youtube-play"></i></span> Sources</h3>
                </div>
                <ul class="link-list">
                    @foreach ($links as $link)
                    <li class="item">
                        <?php $info = parse_url($link) ?>
                        <a href="{{ $link }}" title="{{ $link }}">
                            <h3><span class="text {{ $info['scheme'] == 'https' ? 'is-success' : 'is-danger' }}">{{ $info['scheme'] }}://</span>{{$info['host'] }}</h3>
                            <p>{{ $info['path'] }}</p>
                        </a>
                    </li>
                    @endforeach
                    <p class="has-text-right">Links from <a href="http://putlocker.systems/" target="_blank">putlocker</a></p>
                </ul>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('post-footer')
    <form id="deleteEpisode" action="{{ url('/episode', [$episode->id]) }}" method="POST">
        {{ method_field('DELETE') }}
        {!! csrf_field() !!}
    </form>
    <script>window.VIEW = "episode";</script>
@endsection
