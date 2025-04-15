@extends('CMSView::layouts.main')

@section('head')
    <title>Reports</title>
@endsection

@section('content')
    {{-- @dd($class->filters); --}}
    <div class="container-fixed flex flex-col gap-8">
        <div class="flex flex-col gap-8">


            <livewire:reports.report-filter :slug="$slug" />

            <livewire:reports.report-data lazy :slug="$slug" />
        </div>


    </div>
@endsection
