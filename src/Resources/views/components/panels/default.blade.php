@php

$is_header = isset($title) && !empty($title);
$is_footer = isset($footer) && !empty($footer);
@endphp


<div class="card {{$main_class ?? ""}}">

    @if($is_header)
    <div class="card-header  ">
        <div class="card-title">
            {{$title}}
        </div>
       
    </div>
    @endif
  
   <div class="card-body grid gap-5 {{$classes ?? ''}} @if(!$is_header) card-body-rounded-top  @endif @if(!$is_footer) card-body-rounded-bottom @endif">
    {!! $slot !!}
   </div>
     
   @if($is_footer)
   <div class="card-footer  ">
    {!! $footer !!}
    </div>
    @endif
       
</div>