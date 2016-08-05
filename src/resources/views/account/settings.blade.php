@extends('layouts.account')

@section('hero.icon', 'desktop')
@section('hero.title', 'User interface')
@section('hero.content', 'Modify your interface')

@section('main')
<div class="box">
    <form role="form" method="POST" action="{{ url('/account/settings') }}">
        {!! csrf_field() !!}
        <div class="columns is-multiline is-mobile">
            <div class="column is-6">
                <p class="control">
                    <label class="label">Theme</label>
                </p>
                @foreach(['default', 'green', 'inverted'] as $option)
                <p class="control">
                    <label class="radio">
                        <input type="radio" value="{{ $option }}" name="theme" {{ $settings->theme == $option ? 'checked' : '' }} > {{ ucfirst($option) }}
                    </label>
                </p>
                @endforeach
            </div>
        </div>
        <hr>
        <div class="columns is-multiline is-mobile">
            <div class="column is-6">
                <p class="control">
                    <label class="label">Series overview</label>
                </p>
                @foreach(['default', 'fluid'] as $option)
                <p class="control">
                    <label class="radio">
                        <input type="radio" value="{{ $option }}" name="serie_overview" {{ $settings->serie_overview == $option ? 'checked' : '' }} > {{ ucfirst(str_replace('_', ' ', $option)) }}
                    </label>
                </p>
                @endforeach
            </div>

            <div class="column is-6">
                <p class="control">
                    <label class="label">Calendar overview</label>
                </p>
                @foreach(['default', 'fluid'] as $option)
                <p class="control">
                    <label class="radio">
                        <input type="radio" value="{{ $option }}" name="calendar_overview" {{ $settings->calendar_overview == $option ? 'checked' : '' }} > {{ ucfirst(str_replace('_', ' ', $option)) }}
                    </label>
                </p>
                @endforeach
            </div>
        </div>
        <hr>
        <div class="columns is-multiline is-mobile">
            <div class="column is-6">
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
            </div>

            <div class="column is-6">
                <p class="control">
                    <label class="label">HD Images</label>
                </p>
                @foreach(['never', 'size', 'not_on_mobile', 'always'] as $option)
                <p class="control">
                    <label class="radio">
                        <input type="radio" value="{{ $option }}" name="tvdb_load_hd" {{ $settings->tvdb_load_hd == $option ? 'checked' : '' }} > {{ ucfirst(str_replace('_', ' ', $option)) }}
                    </label>
                </p>
                @endforeach
            </div>

            <div class="column is-6">
                <p class="control">
                    <label class="label">Serie fanart position</label>
                </p>
                @foreach(['default', 'bottom', 'never'] as $option)
                <p class="control">
                    <label class="radio">
                        <input type="radio" value="{{ $option }}" name="serie_fanart" {{ $settings->serie_fanart == $option ? 'checked' : '' }} > {{ ucfirst(str_replace('_', ' ', $option)) }}
                    </label>
                </p>
                @endforeach
            </div>

            <div class="column is-6">
                <p class="control">
                    <label class="label">Show serie actor images</label>
                </p>
                @foreach(['yes', 'no'] as $option)
                <p class="control">
                    <label class="radio">
                        <input type="radio" value="{{ $option }}" name="serie_actor_images" {{ $settings->serie_actor_images == $option ? 'checked' : '' }} > {{ ucfirst($option) }}
                    </label>
                </p>
                @endforeach
            </div>
        </div>

        <div class="is-clearfix">
            <button type="submit" class="button is-success is-pulled-right is-small"><span class="icon is-small"><i class="fa fa-pencil"></i></span></button>
        </div>
    </form>
</div>
</section>
@endsection
