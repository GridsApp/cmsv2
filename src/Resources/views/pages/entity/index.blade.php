@extends('CMSView::layouts.main')

@section('content')
    <div class="container-fixed">
   
        {{-- <livewire:entity-components.table :slug="$slug"  /> --}}




        <livewire:components.table-grid :table="$table"  />


        


    </div>
@endsection
