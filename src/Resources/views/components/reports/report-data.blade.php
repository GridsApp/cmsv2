<div class="relative  ">

    <div wire:loading class="min-h-[100px] w-full">
        <div class="bg-white  flex items-center justify-center z-50 border-red-100 shadow w-full p-5 rounded-lg">
            <div class="text-center">


                <i class="fa-solid fa-loader animate-spin  text-black inline-block"></i>
                <p class="mt-2 text-gray-500 text-[12px]">Loading report data please wait ...</p>
            </div>
        </div>
    </div>

    @if (($filters['refine'] ?? 0) == 1)

        <div class="w-full" wire:loading.remove>
             

        <div class="twa-table-card-report ">
            <div>
                <div class="twa-card-header">
                    <h3 class="twa-card-title">{{ $class->label ?? '' }}</h3>

                    <button type="button" wire:click="exportData">
                        <i class="fa-solid fa-download"></i>

                    </button>
                </div>

                <div class="twa-card-body">

                    <table class="twa-table-report table-auto">

                        <thead>
                            <tr>
                                @foreach ($data['columns'] ?? [] as $column)
                                    <th class="cursor-pointer">
                                        {!! $column['label'] !!}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>


                        <tbody>

                            @foreach ($data['rows'] ?? [] as $row)
                                <tr>
                                    @foreach ($data['columns'] ?? [] as $column)
                                        <td> {{ $row[$column['name']] ?? '' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            @if (!empty($data['rows'] ?? []))
                                <tr class="twa-footer-row">
                                    @foreach ($data['columns'] ?? [] as $column)
                                        <td> {{ $data['footer'][$column['name']] ?? '' }}</td>
                                    @endforeach
                                </tr>
                            @endif

                            @if (empty($data['rows']))
                                <tr class="text-center text-gray-500 p-10">
                                    <td colspan="{{ count($data['columns'] ?? []) }}">No data found for the selected
                                        filters.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        </div>

    @endif

</div>
