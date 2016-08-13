@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container">
        @if (env('ALLOW_REGISTER'))
            <div class="columns">
                <div class="column is-narrow">
                    <div class="heading">
                        <h2 class="title">Register</h2>
                    </div>
                    <hr>
                    <form role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}
                        <p class="control">
                            <label for="name">Username</label>
                            <input class="input" type="text" value="{{ old('name') }}" name="name" id="name"/>
                        </p>
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                        <p class="control">
                            <label for="email">Email</label>
                            <input class="input" type="email" value="{{ old('email') }}" name="email" id="email"/>
                        </p>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                        <p class="control">
                            <label for="password">Password</label>
                            <input class="input" type="password" name="password" value="" id="password">
                        </p>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                        <p class="control">
                            <label for="password_confirmation">Confirm Password</label>
                            <input class="input" type="password" name="password_confirmation" value="" id="password_confirmation">
                        </p>
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                        <p class="controler">
                            <label class="checkbox">
                                <input type="checkbox" name="accept_terms">
                                I have read <a target="_blank" href="{{ url('/tos') }}">the terms of service</a>
                            </label>
                        </p>
                        @if ($errors->has('accept_terms'))
                            <span class="help-block">
                                <strong>{{ $errors->first('accept_terms') }}</strong>
                            </span>
                        @endif
                        <div class="is-clearfix">
                            <button type="submit" class="button is-primary is-pulled-right">Register</button>
                        </div>
                    </form>
                </div>               
            </div>
        @endif
    </div>
</section>
@endsection
