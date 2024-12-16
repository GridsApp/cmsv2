@extends('layouts.master')

@section('master-head')
    @yield('head')
@endsection

@section('master-content')
    @include('partials.sidebar')
    <div class="wrapper flex grow flex-col">
        @include('partials.header')
        @yield('content')
    </div>
@endsection