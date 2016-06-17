@extends('layouts.admin')

@section('hero.icon', 'wrench')
@section('hero.title', 'Updates')
@section('hero.content', 'Update application')

@section('main')
<section class="section">
    <div class="container">
        <div class="level is-mobile">
            <div class="level-item has-text-centered">
                <p class="heading">Series</p>
                <p class="title">{{ $serieCount }}</p>
            </div>
            <div class="level-item has-text-centered">
                <p class="heading">Episodes</p>
                <p class="title">{{ $episodeCount }}</p>
            </div>
            <div class="level-item has-text-centered">
                <p class="heading">Users</p>
                <p class="title">{{ $userCount }}</p>
            </div>
        </div>
    </div>   
</section>
@endsection
