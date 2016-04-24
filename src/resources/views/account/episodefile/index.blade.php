@extends('layouts.app')

@section('hero.icon', 'file')
@section('hero.title', 'Files')
@section('hero.content', 'Manage your episode files')

@section('content')
<section class="section">
    <div class="container">
        @foreach ($files as $file)
            <div class="box">
                <div class="media">
                    <div class="media-left">
                        <figure>
                            <img src="{{ $file->episode->serie->poster }}" style="max-height:124px; padding-right:10px;" alt=""/>
                        </figure>
                    </div>
                    <div class="media-content">
                        <form action="{{ url('/episodefile', [$file->id]) }}" method="POST">
                            {{ method_field('DELETE') }}
                            {!! csrf_field() !!}
                            <button class="button is-danger is-pulled-right"><i class="fa fa-trash icon"></i></button>
                        </form>
                        <div class="heading">
                            <h3 class="title"><span class="tag is-success">{{ $file->status }}</span> <a style="color:inherit;" href="{{ action('EpisodeController@show', [$file->episode->serie->id, $file->episode->id]) }}">{{ $file->episode->serie->name }}</a></h3>
                            <p class="subtitle">{{ $file->episode->season_episode }}</p>
                        </div>
                        <div class="content">
                            <p>{{ $file->episode->overview }}</p>
                        </div>
                        <div class="is-clearfix">
                            <a class="button is-success is-pulled-right" href="{{ action('EpisodeController@view', [$file->episode->serie->id, $file->episode->id]) }}">VIEW</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @if (count($files) == 0)
            <div class="message is-info">
                <div class="message-body">
                    No files
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
