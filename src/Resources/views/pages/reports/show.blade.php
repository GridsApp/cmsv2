@extends('CMSView::layouts.main')

@section('head')
    <title>Reports</title>
@endsection

@section('content')
    {{-- @dd($class->filters); --}}
    <div class="container-fixed flex flex-col gap-8">
        <div class="flex flex-col gap-8">

            <div>
                <h3 class="font-bold text-[16px]"> {{$title}}</h3>
            </div>

            <livewire:reports.report-filter :slug="$slug" />


            @php
                $filters = request()->all();
               
                unset($filters['cms_user']);
                unset($filters['permissions']);

            @endphp

            
            {{-- @if(isset($filters['refine']) && $filters['refine'] == 1) --}}
                <livewire:reports.report-data  :pagination="$pagination" :slug="$slug" lazy />
            {{-- @endif --}}
        </div>


    </div>
@endsection
