@extends('layouts.app')

@section('hero.icon', 'pencil')
@section('hero.title', 'Updates')
@section('hero.content', 'Update series')

@section('content')
<section class="section">
    <div class="container">
        <form role="form" method="GET" action="{{ url()->current() }}">
            <label>Get series updated later then </label>
            <p class="control has-addons">
                <input class="input" type="text" value="{{ $timestring }}" name="q" id="name" placeholder="7 days ago"/>
                <button type="submit" class="button is-primary"> GET </button>
            </p>
        </form>
        <hr>
        <form role="form" method="POST" action="{{ url('/admin/update/') }}">
            {{ method_field('PUT') }}
            {!! csrf_field() !!}
            <table class="table">
                <thead>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Name</th>
                    <th>Updated at</th>
                </thead>
                <tbody>
                    @foreach ($series as $serie)
                    <tr>
                        <td><input type="checkbox" name="seriesid[]" value="{{ $serie->id }}"></td>
                        <td>{{ $serie->name }}</td>
                        <td>{{ $serie->updated_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($series) == 0)
                <div class="message is-danger">
                    <div class="message-body">
                        Nothing to update
                    </div>
                </div>
            @endif
            <button type="submit" class="button is-primary">
                Update
            </button>
        </form>
    </div>   
</section>
@endsection
