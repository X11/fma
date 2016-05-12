@extends('layouts.app')

@section('hero.icon', 'users')
@section('hero.title', 'Users')
@section('hero.content', 'Manage users')

@section('content')
<section class="section">
    <div class="container">
        <form role="form" method="GET" action="{{ url()->current() }}">
            <p class="control has-addons">
                <input class="input" type="text" value="" name="q" id="name"/>
                <button type="submit" class="button is-primary"> Search </button>
            </p>
        </form>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column">
                <table class="table">
                    <thead>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    </thead>
                    <tbody>
                        @if (count($users) == 0)
                            <div class="message is-danger">
                                <div class="message-body">
                                    Nothing to display
                                </div>
                            </div>
                        @endif
                        @foreach ($users as $user)
                        <tr>
                            <td><a href="{{ url()->action('UserController@show', [$user->id]) }}">{{ $user->name }}</a></td>
                            <td>{{ $user->email }}</td>
                            <td class="has-text-centered"><span class="tag {{ $role_tags[$user->role] }}"> {{ $user->role  }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @include('partial.pagination', ['items' => $users])
            </div>
            <div class="column is-one-third">
                <div class="heading">
                    <h2 class="subtitle">Invite</h2>
                </div>
                <form role="form" method="POST" action="{{ url('/admin/user/invite') }}">
                    {!! csrf_field() !!}
                    <p class="control">
                        <label for="name">Name</label>
                        <input class="input" type="name" value="" name="name" id="name" required/>
                    </p>
                    <p class="control">
                        <label for="email">E-mail</label>
                        <input class="input" type="email" value="" name="email" id="email" required/>
                    </p>
                    <button type="submit" class="button is-primary is-pulled-right"> Invite </button>
                </form>
            </div>
        </div>
    </div>   
</section>
@endsection
