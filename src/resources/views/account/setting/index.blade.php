@extends('layouts.app')

@section('hero.icon', 'cog')
@section('hero.title', 'Settings')
@section('hero.content', 'Manage your user settings')

@section('content')
<section class="section">
    <div class="container">
        <form role="form" method="POST" action="{{ url('/account/setting') }}">
            {!! csrf_field() !!}
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
                    <div class="column">
                        <div class="content">
                            <p>To change your profile picture head over to <a href="http://en.gravatar.com/" target="_blank">gravatar</a> and register your email.</p>
                        </div>
                    </div>
                    <div class="column">
                        <label class="label">Change password [WIP]</label>
                        <p class="control">
                            <input class="input" type="text" placeholder="Old password">
                        </p>
                        <p class="control">
                            <input class="input" type="text" placeholder="New password">
                        </p>
                        <p class="control">
                            <input class="input" type="text" placeholder="Re new password">
                        </p>
                    </div>
                </div>
               <div class="is-clearfix">
                    <button type="submit" class="button is-success is-pulled-right is-small"><span class="icon is-small"><i class="fa fa-pencil"></i></span></button>
                </div>
            </div>
        </form>
        <hr>
        <form role="form" method="POST" action="{{ url('/account/setting') }}">
            {!! csrf_field() !!}
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
                <p class="control">
                    <label class="label">Themes</label>
                </p>
                <p class="control">
                    <label class="radio">
                        <input type="radio" value="default" name="theme" {{ $settings->theme == "default" ? 'checked' : '' }} > Default
                    </label>
                </p>
                <p class="control">
                    <label class="radio">
                        <input type="radio" value="dark" name="theme" {{ $settings->theme == "dark" ? 'checked' : '' }}> Dark
                    </label>
                </p>
                <p class="control">
                    <label class="radio">
                        <input type="radio" value="green" name="theme" {{ $settings->theme == "green" ? 'checked' : '' }}> Green
                    </label>
                </p>
                <p class="control">
                    <label class="radio">
                        <input type="radio" value="inverted" name="theme" {{ $settings->theme == "inverted" ? 'checked' : '' }}> Inverted
                    </label>
                </p>
                <div class="is-clearfix">
                    <button type="submit" class="button is-success is-pulled-right is-small"><span class="icon is-small"><i class="fa fa-pencil"></i></span></button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
