@extends('layouts.app')

@section('content')
<section class="section landing-info">
    <div class="img-parent"><img src="{{ asset('img/fanart.png') }}" data-src="{{ $fanart }}" alt=""/></div>
    <div class="landing-column">
        <div class="heading">
            <h1 class="title">FMA</h1>
            <p class="subtitle">Feeding my addiction</p>
        </div>
        <hr>
        <form role="form" method="POST" action="{{ url('/login') }}">
            {!! csrf_field() !!}
            <p class="control has-icon">
                <input class="input" type="email" placeholder="Email" value="{{ old('email') }}" name="email" id="email">
                <i class="fa fa-envelope"></i>
            </p>
            <p class="control has-icon">
                <input class="input" type="password" placeholder="Password" name="password" value="" id="password">
                <i class="fa fa-lock"></i>
            </p>
            <p class="control">
                <button class="button is-success">Login</button>
                <a class="button is-link" href="{{ url('/register') }}">Register</a>
            </p>
        </form>

        <footer>
            <div class="content has-text-right">
                <p><strong>FMA</strong> build to feed the addiction</p>
                @if (Auth::check())
                <p>Content provided by <a href="https://www.thetvdb.com">The TVDB</a></p>
                @endif
            </div>
            <div class="content has-text-right">
                <p>&copy; {{ date('Y') }} Feeding the addiction</p>
            </div>
        </footer>
    </div>
</section>
@endsection
