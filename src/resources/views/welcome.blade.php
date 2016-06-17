@extends('layouts.app')

@section('content')
<section class="section landing-info" style="background-image: url({{ $fanart }});">
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
            </p>
        </form>
    </div>
</section>
@endsection
