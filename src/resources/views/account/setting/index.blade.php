@extends('layouts.app')

@section('hero.icon', 'cog')
@section('hero.title', 'Settings')
@section('hero.content', 'Manage your user settings')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column is-half">
                <div class="box">
                    <div class="media">
                        <div class="media-left">
                            <i class="fa fa-user icon is-large"></i>
                        </div>
                        <div class="media-content">
                            <div class="heading">
                                <h3 class="title">profile</h3>
                            </div>
                            <div class="content">
                                <p>Edit your profile</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="columns">
                        <div class="column is-narrow has-text-centered">
                            <img style="width: 100%; max-width:150px" src="http://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=300&default=mm" alt="Image">
                            <div class="has-text-centered">
                                <hr>
                                <p class="tag is-medium {{ $role_tags[$user->role] }}"> {{ $user->role  }}</p>
                            </div>
                        </div>
                        <div class="column">
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
                                <button type="submit" class="button is-success is-pulled-right is-small"><span class="icon is-small"><i class="fa fa-pencil"></i></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="box">
                    <div class="media">
                        <div class="media-left">
                            <i class="fa fa-desktop icon is-large"></i>
                        </div>
                        <div class="media-content">
                            <div class="heading">
                                <h3 class="title">user interface</h3>
                            </div>
                            <div class="content">
                                <p>Modify your interface</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <form role="form" method="POST" action="{{ url('/account/setting') }}">
                        {!! csrf_field() !!}
                        <p class="control">
                            <label class="label">Themes</label>
                        </p>
                        @foreach(['default', 'green', 'inverted'] as $option)
                        <p class="control">
                            <label class="radio">
                                <input type="radio" value="{{ $option }}" name="theme" {{ $settings->theme == $option ? 'checked' : '' }} > {{ ucfirst($option) }}
                            </label>
                        </p>
                        @endforeach

                        <p class="control">
                            <label class="label">Header</label>
                        </p>
                        @foreach(['default', 'primary', 'light', 'dark'] as $option)
                        <p class="control">
                            <label class="radio">
                                <input type="radio" value="{{ $option }}" name="header" {{ $settings->header == $option ? 'checked' : '' }} > {{ ucfirst($option) }}
                            </label>
                        </p>
                        @endforeach

                        <p class="control">
                            <label class="label">Images</label>
                        </p>
                        @foreach(['never', 'size', 'not_on_mobile', 'always'] as $option)
                        <p class="control">
                            <label class="radio">
                                <input type="radio" value="{{ $option }}" name="tvdb_load_hd" {{ $settings->tvdb_load_hd == $option ? 'checked' : '' }} > {{ ucfirst(str_replace('_', ' ', $option)) }}
                            </label>
                        </p>
                        @endforeach

                        <div class="is-clearfix">
                            <button type="submit" class="button is-success is-pulled-right is-small"><span class="icon is-small"><i class="fa fa-pencil"></i></span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
