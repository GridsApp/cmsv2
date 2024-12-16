@extends('layouts.master')

@section('master-head')
    @yield('head')
@endsection

@section('master-content')
<div class="grid lg:grid-cols-2 grow twa-login-container">
    <div class="flex justify-center items-center p-8 lg:p-10 order-2 lg:order-1">
        <div class="card max-w-[370px] w-full">
         
            <livewire:entity-forms.login-form   />

        </div>
    </div>

    <div
        class="lg:rounded-xl lg:border lg:border-gray-200 lg:m-5 order-1 lg:order-2 bg-top xxl:bg-center xl:bg-cover bg-no-repeat branded-bg">
        <div class="flex flex-col p-8 lg:p-16 gap-4">
            <a href="/metronic/tailwind/demo1/">
                <svg id="Group_54" data-name="Group 54" xmlns="http://www.w3.org/2000/svg" width="43.798" height="25.475" viewBox="0 0 43.798 25.475" fill="white">
                    <g id="Group_53" data-name="Group 53" transform="translate(0 0)">
                        <rect id="Rectangle_129" data-name="Rectangle 129" width="6.446" height="24.647" rx="3.223" transform="translate(0 1.697) rotate(-15.259)"></rect>
                        <rect id="Rectangle_130" data-name="Rectangle 130" width="6.446" height="24.647" rx="3.223" transform="translate(10.445 1.697) rotate(-15.259)"></rect>
                        <rect id="Rectangle_131" data-name="Rectangle 131" width="6.446" height="24.647" rx="3.223" transform="matrix(0.965, -0.263, 0.263, 0.965, 31.092, 1.697)"></rect>
                    </g>
                    <circle id="Ellipse_2" data-name="Ellipse 2" cx="3.366" cy="3.366" r="3.366" transform="translate(23.863 9.371)"></circle>
                </svg>
            </a>
            <div class="flex flex-col mt-[20px] gap-4 text-white">
                <h3 class="text-[30px] font-semibold ">
                    Cinema Access Portal
                </h3>
               
                <p class="text-[14px] max-w-[90%]">

                    Your centralized hub for managing movies, showtimes, and theaters <br> 
                    
                    to help you streamline operations and deliver a seamless cinematic experience to your audience
                </p>

                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

