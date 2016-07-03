@extends('layouts.account')

@section('hero.icon', 'user')
@section('hero.title', 'Security')

@section('main')
<div>
    <ul class="feeds">
        @foreach($logs as $log)
        <li class="feed-entry {{ $log == $logs->last() ? 'is-last' : '' }}">
            <div class="feed">
                <span></span>
                <div>
                    <h3>{{ $log->type }}</h3>
                    <p>{{ $log->created_at->diffForHumans() }} from {{ $log->IP }}</p>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</div>
</section>
@endsection
