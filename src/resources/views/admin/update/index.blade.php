@extends('layouts.app')

@section('hero.icon', 'wrench')
@section('hero.title', 'Updates')
@section('hero.content', 'Update application')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column">
                <form role="form" method="POST" action="{{ url('/admin/update/series') }}">
                    {{ method_field('PUT') }}
                    {!! csrf_field() !!}
                    <label>Update series before</label>
                    <p class="control has-addons">
                        <input class="input" type="text" value="last week" name="q" id="name" placeholder="7 days ago"/>
                        <button type="submit" class="button is-primary"> UPDATE </button>
                    </p>
                </form>
                <br>
                <form role="form" method="POST" action="{{ url('/admin/update/cache') }}">
                    {{ method_field('PUT') }}
                    {!! csrf_field() !!}
                    <label>Remove cache</label>
                    <p class="control has-addons">
                        <button type="submit" class="button is-danger"> REMOVE </button>
                    </p>
                </form>
            </div>
            <div class="column">
                <div class="level">
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
        </div>
    </div>   
</section>
@endsection
