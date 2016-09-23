@extends('layouts.account')

@section('hero.icon', 'user')
@section('hero.title', 'Profile')
@section('hero.content', 'Edit your profile')

@section('main')
<div class="box">
    <div class="columns">
        <div class="column is-narrow has-text-centered">
            <img style="width: 100%; max-width:150px" src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=300&default=mm" alt="Image">
            <div class="has-text-centered">
                <hr>
                <p class="tag is-medium {{ $role_tags[$user->role] }}"> {{ $user->role  }}</p>
            </div>
        </div>
        <div class="column">
            <div class="heading">
                <h2 class="title">{{ $user->name }}</h2>
            </div>
            <form role="form" method="POST" action="{{ url('/password/change') }}">
                {!! csrf_field() !!}
                <div class="content">
                    <p>To change your profile picture head over to <a href="http://en.gravatar.com/" target="_blank">gravatar</a> and register your email.</p>
                </div>
                <label class="label">Change password</label>
                <p class="control">
                    <input class="input" type="password" placeholder="Old password" name="old_password">
                    @if ($errors->has('old_password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('old_password') }}</strong>
                        </span>
                    @endif
                </p>
                <p class="control">
                    <input class="input" type="password" placeholder="New password" name="new_password">
                    @if ($errors->has('new_password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('new_password') }}</strong>
                        </span>
                    @endif
                </p>
                <p class="control">
                    <input class="input" type="password" placeholder="Re new password" name="new_password_confirmation">
                    @if ($errors->has('new_password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                        </span>
                    @endif
                </p>
                <button type="submit" class="button is-success is-pulled-right is-small">Save</button>
            </form>
        </div>
    </div>
</div>
</section>
@endsection
