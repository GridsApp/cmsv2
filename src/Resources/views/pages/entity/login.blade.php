@extends('layouts.master')

@section('master-head')
    @yield('head')
@endsection

@section('master-content')
    <livewire:entity-forms.login-form :slug="$slug" :id="$id" />
@endsection
