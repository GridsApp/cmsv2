@extends('CMSView::layouts.main')

@section('content')
    @php

        // dd(session('cms_user_permissions'));

        $cms_user = request()->input('cms_user');

        if ($cms_user) {
            $email = $cms_user['email'] ?? '';
            $explode = explode(' ', $cms_user['name']);
            $firstName = $explode[0] ?? '';
            $lastName = $explode[1] ?? '';

            $initials = strtoupper(
                (strlen($firstName) > 0 ? substr($firstName, 0, 1) : '') .
                    (strlen($lastName) > 0 ? substr($lastName, 0, 1) : ''),
            );
        } else {
            $email = null;
            $initials = null;
        }

    @endphp
    <div class="container-fixed">
        <h1 class="text-[14px] font-bold">Dashboard</h1>

        <div class="grid grid-cols-2 gap-7 mt-9">
          <div>
            @component('CMSView::components.panels.default', ['classes' => 'bg-[#fcfcfc] h-full ring-1 ring-gray-300'])
            <div class="text-gray-800">


                <div class="text-[14px] font-bold block">
                    Welcome {{$cms_user['name'] }}
                </div>
                <br>
                <div class="text-[14px]">
                    Created: {{ now()->parse($cms_user['created_at'])->diffForHumans()  }}
                </div>



            </div>
        @endcomponent
          </div>

<div>

    @component('CMSView::components.panels.default', ['classes' => 'bg-gray-100 ring-1 ring-gray-300'])
    <div class=" text-gray-800">


        <div class="text-[14px] font-bold">
          
            {{  env('DASHBOARD_TEXT')}}
        </div>
        <br>
        <div class="text-[14px]">
            Current version:	dev-main
        </div>



    </div>
@endcomponent
</div>
        </div>


    </div>
@endsection
