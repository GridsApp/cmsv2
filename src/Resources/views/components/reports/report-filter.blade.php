<form class="card " wire:submit.prevent="applyFilters"   x-data="{disabled : false}" x-on:query-completed.window="disabled = false" x-on:start-report.window="disabled = true">
    <div class="card-body grid gap-5    card-body-rounded-bottom ">
        @foreach ($class->filters as $filter)
            {!! field($filter) !!}
        @endforeach

        <div class="flex w-full gap-4">
            <a href="{{route('show-report' , ['slug' => $slug])}}" class="btn bg-gray-300 text-white w-full">Clear filters </a>
            <button :disabled="disabled"  class="btn btn-primary w-full">Refine </button>
        </div>
    </div>
</form>
