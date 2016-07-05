@extends('layouts.admin')

@section('hero.title', 'Activity')

@section('main')
<div>
    <ul class="feeds">
        @foreach($logs as $log)
        <li class="feed-entry {{ $log == $logs->last() ? 'is-last' : '' }}">
            <div class="feed">
                <span></span>
                <div>
                    <h3>{{ $log->action }}</h3>
                    <p>{{ $log->created_at->diffForHumans() }} @if($log->type == 'account') from {{ $log->IP }} @endif</p>
                    @if ($log->data)
                    <br>
                        @foreach($log->data as $key => $value)
                        <p><strong style="text-transform: uppercase;">{{ $key }}:</strong> {{ $value }}</p>
                        @endforeach
                    @endif
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</div>
</section>
@endsection
