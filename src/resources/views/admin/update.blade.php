@extends('layouts.admin')

@section('hero.icon', 'wrench')
@section('hero.title', 'Updates')
@section('hero.content', 'Update application')

@section('main')
<div class="box">
    <form role="form" method="POST" action="{{ url('/admin/update/serie') }}">
        {{ method_field('PUT') }}
        {!! csrf_field() !!}
        <label>Series updated before</label>
        <p class="control has-addons">
            <input class="input" type="text" value="last week" name="q" id="name" placeholder="7 days ago"/>
            <button type="submit" class="button is-primary"> UPDATE </button>
        </p>
    </form>
    <br>
    <form role="form" method="POST" action="{{ url('/admin/update/episode') }}">
        {{ method_field('PUT') }}
        {!! csrf_field() !!}
        <label>Episodes updated before</label>
        <p class="control has-addons">
            <input class="input" type="text" value="last week" name="q" id="name" placeholder="7 days ago"/>
            <button type="submit" class="button is-primary"> UPDATE </button>
        </p>
    </form>
</div>   
@endsection
