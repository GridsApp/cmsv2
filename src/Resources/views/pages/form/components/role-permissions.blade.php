<form wire:submit.prevent="save">
    <div class="container-content-height role-permission-page">
        <div class="container-fixed">
            <div class="mb-5">
                <div class="flex flex-col gap-10">

                    {{-- @dd($filteredMenu); --}}

                    @foreach ($filteredMenu as $index => $menuItem)

    
                        @component('CMSView::components.panels.default', ['title' => $menuItem['label']])
                        @foreach ($menuItem['permissions'] as $per_index => $permission)

                            <label class="flex flex-row gap-3 contain-box">
                                <input type="checkbox" class="checkbox " wire:model="filteredMenu.{{$index}}.permissions.{{$per_index}}.selected" id="">
                                <div class="text-[12px] ">
                                 {{$permission['label']}}
                                </div>
                            </label>
                            @endforeach

                
                        @endcomponent
                    @endforeach

                 
                </div>
            </div>

        </div>
    </div>

    <div class="container-fixed my-4">
        @component('CMSView::components.panels.default', ['classes' => 'bg-[#fcfcfc] ring-1 ring-gray-300'])
            <div class="flex justify-center gap-4">
                {!! link_button('Cancel', '#', 'secondary') !!}

                <button class="btn btn-primary"> Submit </button>
            </div>
        @endcomponent
    </div>

</form>
