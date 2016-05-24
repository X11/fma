@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container">
        <div class="heading">
            <h2 class="title">Login</h2>
        </div>
        <hr>
        <form role="form" method="POST" action="{{ url('/login') }}">
            {!! csrf_field() !!}
            <div class="columns">
                <div class="column">
                    <p class="control">
                        <label for="email">Email</label>
                        <input class="input" type="email" value="{{ old('email') }}" name="email" id="email"/>
                    </p>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="column">
                    <p class="control">
                        <label for="password">Password</label>
                        <input class="input" type="password" name="password" value="" id="password">
                    </p>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <p class="control">
                <label class="checkbox">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
            </p>
            <div class="is-clearfix">
                <button type="submit" class="button is-primary is-pulled-right">Login</button>
                <a class="button is-link is-pulled-right" href="{{ url('/password/reset') }}"><small>Forgot Your Password?</small></a>
            </div>
        </form>
    </div>
</section>
@endsection
