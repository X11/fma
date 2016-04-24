@extends('layouts.app')

@section('hero.icon', 'user')
@section('hero.title', $profile->name)
@section('hero.content', $profile->role)

@section('content')
<section class="section profile">
    <div class="container">
        <div class="columns">
            <div class="column is-quarter profile-image" style="order: 2;">
                <div class="has-text-centered">
                    <img style="width: 100%; max-width: 300px;" src="http://www.gravatar.com/avatar/{{ md5(strtolower(trim($profile->email))) }}?s=300&default=mm" alt="Image">
                    <div class="column has-text-centered">
                        <p class="tag is-medium {{ $role_tags[$profile->role] }}"> {{ $profile->role  }}</p>
                    </div>
                </div>
                <hr>
                <div class="content">
                    @if (Auth::user()->isModerator())
                        <p><strong>Email</strong><br>{{ $profile->email }}</p>
                    @endif
                    <p><strong>Last login</strong><br>{{ $profile->last_login }}</p>
                    <p><strong>Registered</strong><br>{{ $profile->created_at->toDateString() }}</p>
                </div>
                <hr>
                @if (Auth::user()->isAdmin() && $profile->role_index < Auth::user()->role_index)
                    <form role="form" method="POST" action="{{ url('/admin/user', [$profile->id, 'role']) }}">
                        {!! csrf_field() !!}
                        <label class="label">User role</label>
                        <p class="control has-addons">
                            <span class="select">
                                <select name="role">
                                    @foreach ($profile->USER_ROLES as $i => $role)
                                        <option {{ $i < Auth::user()->role_index ? '' : 'disabled' }} {{ $role == $profile->role ? 'selected' : '' }} value="{{$i}}">{{ $role }}</option>
                                    @endforeach
                                </select>
                            </span>
                            <button class="button is-primary" type="submit"><span class="icon"><i class="fa fa-edit"></i></span></button>
                        </p>
                    </form>
                    <hr>
                @endif
            </div>
            <div class="column">
                <div class="columns is-mobile">
                    <div class="column has-text-centered">
                        <p class="heading">Series</p>
                        <p class="title">{{ $profile->watching()->count() }}</p>
                    </div>
                    <div class="column  has-text-centered">
                        <p class="heading">Watched</p>
                        <p class="title">{{ $profile->watched()->count() }}</p>
                    </div>
                </div>
                <hr>
                @foreach ($series as $serie)
                    <div class="media">
                        <figure class="media-image">
                            <img width="100%" src="{{ asset('img/poster.png') }}"  data-src="{{ $serie->poster }}" alt="" style="max-height:124px; padding-right:10px;"/>
                        </figure>
                        <div class="media-content content">
                            <h2><a href="{{ url('/serie', [$serie->id]) }}">{{ $serie->name }}</a></h2>
                            <p>{{ $serie->overview }}</p>
                        </div>
                    </div>
                @endforeach
                @include('partial.pagination', ['items' => $series])
            </div>
        </div>
    </div>
</section>
@endsection
