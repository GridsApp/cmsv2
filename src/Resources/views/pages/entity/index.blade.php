@extends('layouts.main')

@section('content')
    <div class="container-fixed">
        <livewire:entity-components.table :slug="$slug"  />
    </div>
@endsection
