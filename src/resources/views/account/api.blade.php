@extends('layouts.account')

@section('hero.icon', 'user')
@section('hero.title', 'API')

@section('main')
<div class="box">
    <div class="is-clearfix">
        <button form="resetApi" class="is-pulled-right button is-danger">Reset API token</button>
    </div>
    <br>
    <div class="content">
        <p>FMA Provides a small API which you can access @ <a href="https://feedmyaddiction.xyz/api/v1/"><code>https://feedmyaddiction.xyz/api/v1/</code></a>. Just append <code>?api_token=&lt;KEY&gt;</code> to the requested route or add <code>api-key: &lt;KEY&gt;</code> to your headers.</p>
        <p>Your API key is <code>{{ $key }}</code></p>
    </div>
    @foreach($api_endpoints as $endpoints)
        <table class="table">
            <thead>
                <tr><th>METHOD</th><th>URL</th><th>EXTRA</th></tr>
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

@section('post-footer')
    <form id="resetApi" action="{{ action('UserController@resetApiToken') }}" method="POST">
        {!! csrf_field() !!}
    </form>
@endsection
