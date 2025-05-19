@extends('CMSView::layouts.main')

@section('head')
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/sort@3.x.x/dist/cdn.min.js"></script>
<style>
    .sortable-ghost {
        opacity: .5 !important;
    }
</style>
@endsection

@section('content')
<div x-data="{ sorted: [], addSortable(item) { this.sorted.push(item + ''); }, handleSorting() { let ids = Array.from(this.$refs.listgroup.children).map(el => el.dataset.id);
        fetch('{{ route('cms-entity.sorting.post', ['slug' => $entity->slug]) }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ ids }) });
        this.sorted = []; } }" class="container-fixed">
<table class="twa-table table-auto">
    <thead>
        <tr>
            <th colspan="2">
                <div class="flex items-center justify-between"> <span>Sort: {{ $entity->entity }}</span> <button
                        class="btn btn-primary" @click="handleSorting" type="button"> Apply Changes </button> </div>
            </th>
        </tr>
    </thead>
    <tbody x-ref="listgroup" x-sort="addSortable($item)">
        @foreach ($rows as $row)
            <tr :data-id="{{ $row->id }}" x-sort:item="{{ $row->id }}" class="cursor-move">
                <td style="width: 50px" :class="sorted.includes('{{ $row->id }}') ? '!bg-red-50' : ''"> <svg
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                        color="#000000" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3 5C3 4.44772 3.44772 4 4 4L20 4C20.5523 4 21 4.44772 21 5C21 5.55229 20.5523 6 20 6L4 6C3.44772 6 3 5.55228 3 5Z"
                            fill="#000000"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3 12C3 11.4477 3.44772 11 4 11L20 11C20.5523 11 21 11.4477 21 12C21 12.5523 20.5523 13 20 13L4 13C3.44772 13 3 12.5523 3 12Z"
                            fill="#000000"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3 19C3 18.4477 3.44772 18 4 18L20 18C20.5523 18 21 18.4477 21 19C21 19.5523 20.5523 20 20 20L4 20C3.44772 20 3 19.5523 3 19Z"
                            fill="#000000"></path>
                    </svg> </td>
                <td :class="sorted.includes('{{ $row->id }}') ? '!bg-red-50' : ''"> {{ $row->label }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
