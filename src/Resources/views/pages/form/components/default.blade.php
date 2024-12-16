<div>
    <form wire:submit="save">
        <div class="@if ($unique_id) container-content-height-2 @else container-content-height @endif">
            @if ($unique_id)
                <div class="grid grid-cols-12 gap-5">
                    @foreach ($fields ?? [] as $field)

                        

                        {!! field($field, 'col-span-12' , null , false , false) !!}
                    @endforeach
                </div>
            @else
                <div class="container-fixed  ">
                    <div class="grid grid-cols-12 gap-5">
                        @foreach ($fields ?? [] as $field)
                            {!! field($field, 'col-span-7') !!}
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <div class="@if (!$unique_id) container-fixed @endif my-4">
            @component('components.panels.default', ['classes' => 'bg-[#fcfcfc] ring-1 ring-gray-300'])
                <div class="flex justify-center gap-4">
                    @if (!$unique_id)    {!! link_button('Cancel', '#', 'secondary') !!} @endif
                    {!! button("'Submit'", 'primary', '', 'submit', 'text-[12px]') !!}
                </div>
            @endcomponent
        </div>
    </form>
</div>
