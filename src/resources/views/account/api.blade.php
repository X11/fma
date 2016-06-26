@extends('layouts.account')

@section('hero.icon', 'user')
@section('hero.title', 'API')

@section('main')
<div class="box">
    <div class="content">
        <div class="heading">
            <h2 class="title">API</h2>
        </div>
        <p>FMA Provides a small API. You can access it at <a href="https://feedmyaddiction.xyz/api/v1/">https://feedmyaddiction.xyz/api/v1/</a>. Just append <code>?api_token=&lt;KEY&gt;</code> to the requested route or add <code>api-key: &lt;KEY&gt;</code> to your headers.</p>
        <p>Your API key is <code>{{ $key }}</code></p>
    </div>
</div>
</section>
@endsection
