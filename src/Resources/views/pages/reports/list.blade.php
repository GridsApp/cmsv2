@extends('CMSView::layouts.main')


@section('head')
    <title>Reports</title>
@endsection
@section('content')
    <div class="container-fixed mb-10">
        <div class="twa-table-card ">

            <div class="twa-card-header ">
                <h3 class="twa-card-title">Reports</h3>
            </div>

            <div class=" twa-card-body">

                {{-- <div class="reports-group-title"> Admits </div> --}}

                @foreach($result as $report)
                <a href="{{route('cms-show-report' , ['slug' => $report['slug']]) }}" class="reports-title">{{$report['label']}}</a>

                @endforeach
        
            </div>
        </div>
        {{-- <h1>Admits Reports</h1>
        <h1>Admits Reports By Type</h1>
        <h1>Admits Reports By Distributor</h1> --}}
    </div>
@endsection
