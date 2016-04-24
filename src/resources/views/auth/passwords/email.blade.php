@extends('layouts.app')

@section('hero.title', 'Request password reset')

<!-- Main Content -->
@section('content')
<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column is-quarter"> </div>
            <div class="column">
                @if (session('status'))
                    <div class="message is-danger">
                        <div class="message-body">
                            {{ session('status') }}
                        </div>
                    </div>
                @endif
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                    {!! csrf_field() !!}
                    <p class="control">
                        <label>E-Mail Address</label>
                        <input type="email" class="input" name="email" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <strong>{{ $errors->first('email') }}</strong>
                        @endif
                    </p>
                    <button type="submit" class="button is-primary">
                        Send Password Reset Link
                    </button>
                </form>
            </div>
            <div class="column is-qaurter"> </div>
        </div>
    </div>
</section>
@endsection
