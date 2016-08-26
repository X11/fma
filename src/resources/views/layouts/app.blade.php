@extends('layouts.base')

@section('body')
    @include('partial.header')

    @if (isset($breadcrumbs))
        @include('partial.breadcrumbs')
    @endif

    @include('partial.status')

    @yield('content')

    @if (isset($breadcrumbs))
        @include('partial.breadcrumbs')
    @endif

    @include('partial.footer')
@endsection
