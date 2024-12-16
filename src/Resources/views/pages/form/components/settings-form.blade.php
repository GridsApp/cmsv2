<form wire:submit.prevent="handleSubmit">
    <div class="container-content-height no-breadcrumbs">
        <div class="container-fixed">
           
            @forelse ($group_settings as $group => $settings)
                @component('components.panels.default', ['title' => $group])
                    <div class="grid grid-cols-12 gap-3">
                        @foreach ($settings as $setting)
                            @php
                                $field = config('fields.' . $setting['field']);
                                if (!$field) {
                                    continue;
                                }
                            @endphp
                            {!! field($field) !!}
                            @endforeach
                    </div>
       
            @endcomponent

            @empty

            <div>No settings found!</div>

            @endforelse
        </div>
    </div>

    <div class="container-fixed my-4">
        @component('components.panels.default', ['classes' => 'bg-[#fcfcfc] ring-1 ring-gray-300'])
            <div class="flex justify-center gap-4">
                {!! link_button('Cancel', '#', 'secondary') !!}

                <button class="btn btn-primary"> Submit </button>
            </div>
        @endcomponent
    </div>

</form>
