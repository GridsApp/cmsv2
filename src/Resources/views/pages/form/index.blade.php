@extends('layouts.main')

@section('content')
<livewire:entity-forms.form :slug="$slug" :id="$id" />
@endsection
