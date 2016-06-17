@extends('layouts.admin')

@section('hero.icon', 'wrench')
@section('hero.title', 'Updates')
@section('hero.content', 'Update application')

@section('main')
<div class="box">
    <form role="form" method="POST" action="{{ url('/admin/cache') }}">
        {{ method_field('PUT') }}
        {!! csrf_field() !!}
        <label>Remove cache</label>
        <p class="control has-addons">
            <button type="submit" class="button is-danger"> REMOVE </button>
        </p>
    </form>
</div>   
@endsection
