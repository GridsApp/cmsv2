@extends('CMSView::layouts.master')

@section('master-head')
    @yield('head')
@endsection

@section('master-content')
    @include('CMSView::partials.sidebar')
    {{-- <div class="wrapper flex grow flex-col"> --}}
        @include('CMSView::partials.header')
        @yield('content')
    {{-- </div> --}}
@endsection