@extends('layouts.base')

@section('body')
<section class="section img-section landing-info">
    <div class="img-parent"><img src="{{ asset('img/fanart.png') }}" data-src="{{ $fanart }}" alt=""/></div>
    <div class="landing-column">
        <div class="heading">
            <h1 class="title">FMA</h1>
            <p class="subtitle">Feed my addiction</p>
        </div>
        <hr>
        @if(Route::current()->getPath() != 'register')
            <form role="form" method="POST" action="{{ url('/login') }}">
                {!! csrf_field() !!}
                <p class="control has-icon">
                    <input class="input" type="email" placeholder="Email" value="{{ old('email') }}" name="email" id="email">
                    <i class="fa fa-envelope"></i>
                    @if ($errors->has('email'))
                        <span class="help is-danger">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </p>
                <p class="control has-icon">
                    <input class="input" type="password" placeholder="Password" name="password" value="" id="password">
                    <i class="fa fa-lock"></i>
                    @if ($errors->has('password'))
                        <span class="help is-danger">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </p>
                <p class="control">
                    <label class="checkbox">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                </p>
                <p class="control">
                    <button class="button is-success">Login</button>
                    @if (env('ALLOW_REGISTER'))
                    <a class="button is-link" href="{{ url('/register') }}">Register</a>
                    @endif
                </p>
            </form>
        @else
            <form role="form" method="POST" action="{{ url('/register') }}">
                {!! csrf_field() !!}
                <p class="control">
                    <label for="name">Username</label>
                    <input class="input" type="text" value="{{ old('name') }}" name="name" id="name"/>
                    @if ($errors->has('name'))
                        <span class="help">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </p>
                <p class="control">
                    <label for="email">Email</label>
                    <input class="input" type="email" value="{{ old('email') }}" name="email" id="email"/>
                    @if ($errors->has('email'))
                        <span class="help">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </p>
                <p class="control">
                    <label for="password">Password</label>
                    <input class="input" type="password" name="password" value="" id="password">
                    @if ($errors->has('password'))
                        <span class="help">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </p>
                <p class="control">
                    <label for="password_confirmation">Confirm Password</label>
                    <input class="input" type="password" name="password_confirmation" value="" id="password_confirmation">
                    @if ($errors->has('password_confirmation'))
                        <span class="help">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </p>
                <p class="control">
                    <label class="checkbox">
                        <input type="checkbox" name="accept_terms">
                        I have read the <a target="_blank" href="{{ url('/tos') }}">terms of service</a>
                    </label>
                    @if ($errors->has('accept_terms'))
                        <span class="help">
                            <strong>{{ $errors->first('accept_terms') }}</strong>
                        </span>
                    @endif
                </p>
                <p class="control">
                    <button type="submit" class="button is-primary">Register</button>
                    @if (env('ALLOW_REGISTER'))
                    <a class="button is-link" href="{{ url('/') }}">Login</a>
                    @endif
                </p>
            </form>
        @endif
    </div>
</section>
@if ($last_aired)
<section class="section img-section">
    <div class="img-parent"><img class="blur" src="{{ asset('img/fanart.png') }}" data-src="{{ $last_aired->serie->fanart}}" alt=""/></div>
    <div class="section-center">
        <img src="{{ asset('img/poster.png') }}" data-src="{{ $last_aired->serie->poster }}" alt=""/>
        <div>
            <div class="heading">
                <h3 class="title">{{ str_replace(['(', ')'], '', $last_aired->serie->name) }} <span>{{ $last_aired->serie->year }}</span></h3>
                <p class="subtitle"><span class="icon text is-danger"><i class="fa fa-heart"></i></span> {{ $last_aired->serie->rating }}% | {{ $last_aired->serie->seasons }} Season{{ $last_aired->serie->seasons > 1 ? 's' : '' }}</p>
            </div>
            <div class="content">
                <p>{{ str_limit($last_aired->serie->overview, 250) }}</p>
                <div class="is-clearfix">
                    <a class="button is-white is-outlined is-medium is-pulled-right" href="{{ $last_aired->serie->url }}">Read more</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@include('partial.footer')
@endsection
