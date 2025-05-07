<form class="card-filters card-body" wire:submit.prevent="applyFilters" x-data="GeneralFunctions.filterForm()"
    x-on:query-completed.window="disabled = false" x-on:start-report.window="disabled = true">
    <div class=" grid grid-cols-12 gap-3 card-body-rounded-bottom ">
        @foreach ($class->filters as $filter)
            {!! field($filter) !!}
            @if ($filter['name'] === 'date')
                <div class="col-span-12">
                    <div id="placeholder_ajax_date" class="mb-2 text-[12px]" x-html="weekRange"></div>
                </div>
            @endif
        @endforeach

        {{-- <div class="col-span-12"> --}}
   
        {{-- </div> --}}
    </div>
    <div class="flex gap-4">
        <div class=" w-full mt-10">
            <a href="{{ route('show-report', ['slug' => $slug]) }}" class="btn bg-gray-300 text-white w-full">Clear
                filters</a>
        </div>
        <div class=" w-full mt-10">
            <button :disabled="disabled" class="btn btn-primary w-full">Refine</button>
        </div>
      </div>

</form>
