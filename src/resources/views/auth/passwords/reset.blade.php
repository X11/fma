@extends('layouts.app')

@section('hero.title', 'Reset password')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column is-quarter"> </div>
            <div class="column"> 
                <form method="POST" action="{{ url('/password/reset') }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <p class="control">
                        <label>E-Mail Address</label>
                        <input class="input is-disabled" type="email" name="email" value="{{ $email or old('email') }}">
                        @if ($errors->has('email'))
                            <strong>{{ $errors->first('email') }}</strong>
                        @endif
                    </p>
                    <p class="control">
                        <label>Password</label>
                        <input class="input" type="password" name="password">
                        @if ($errors->has('password'))
                            <strong>{{ $errors->first('password') }}</strong>
                        @endif
                    </p>
                    <p class="control">
                        <label class="col-md-4 control-label">Confirm Password</label>
                        <input class="input" type="password" class="form-control" name="password_confirmation">
                        @if ($errors->has('password_confirmation'))
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        @endif
                    </p>
                    <button class="button is-primary" type="submit">Reset password</button>
                </form>
            </div>
            <div class="column is-quarter"> </div>
        </div>
    </div>   
</section>
@endsection
