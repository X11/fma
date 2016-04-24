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
                    <label class="radio">
                        <input type="radio" value="default" name="theme" {{ $settings->theme == "default" ? 'checked' : '' }} > Default
                    </label>
                    <label class="radio">
                        <input type="radio" value="dark" name="theme" {{ $settings->theme == "dark" ? 'checked' : '' }}> Dark
                    </label>
                    <label class="radio">
                        <input type="radio" value="green" name="theme" {{ $settings->theme == "green" ? 'checked' : '' }}> Green
                    </label>
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
