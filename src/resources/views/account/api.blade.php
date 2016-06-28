@extends('layouts.account')

@section('hero.icon', 'user')
@section('hero.title', 'API')

@section('main')
<div class="box">
    <div class="heading">
        <h2 class="title">API</h2>
    </div>
    <div class="content">
        <p>FMA Provides a small API. You can access it at <a href="https://feedmyaddiction.xyz/api/v1/">https://feedmyaddiction.xyz/api/v1/</a>. Just append <code>?api_token=&lt;KEY&gt;</code> to the requested route or add <code>api-key: &lt;KEY&gt;</code> to your headers.</p>
        <p>Your API key is <code>{{ $key }}</code></p>
    </div>
    <div class="heading">
        <h3 class="title">ENDPOINTS</h3>
    </div>
    @foreach($api_endpoints as $endpoints)
        <table class="table">
            <thead>
                <tr><th>METHOD</th><th>URL</th><th>Extra</th></tr>
            </thead>
            <tbody>
                @foreach($endpoints as $endpoint)
                <tr><td><span class="tag {{ $endpoint['label'] }}">{{ $endpoint['method'] }}</span></td><td><code>{{ $endpoint['url'] }}</code></td><td>{{ $endpoint['extra'] }}</td></tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</div>
</section>
@endsection
