<div class="relative  min-h-[200px]">


    @if ($noData && !$isLoading)
    @endif

    @if ($noData && $isLoading)
        <div class="absolute inset-0 bg-white  flex items-center justify-center z-50" wire:poll="getData">
            <div class="text-center">


                <i class="fa-regular fa-loader animate-spin  text-black inline-block"></i>
                <p class="mt-2 text-gray-500 text-[12px]">Loading report data please wait</p>
            </div>
        </div>
    @endif


    @if (!$noData && !$isLoading)


        <div class="twa-table-card-report">
            <div>
                <div class="twa-card-header">
                    <h3 class="twa-card-title">{{ $class->label ?? '' }}</h3>
                </div>

                <div class="twa-card-body">

                    <table class="twa-table-report table-auto">

                        <thead>
                            <tr>
                                @foreach ($data['columns'] as $column)
                                    <th class="cursor-pointer">
                                        {!! $column['label'] !!}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>


                        <tbody>

                            @foreach ($data['rows'] as $row)
                                <tr>
                                    @foreach ($data['columns'] as $column)
                                        <td> {{ $row[$column['name']] ?? '' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            @if (!empty($data['rows']))
                                <tr class="twa-footer-row">
                                    @foreach ($data['columns'] as $column)
                                        <td> {{ $data['footer'][$column['name']] ?? '' }}</td>
                                    @endforeach
                                </tr>
                            @endif

                            @if (empty($data['rows']) )
                                <tr class="text-center text-gray-500 p-10">
                                    <td colspan="{{ count($data['columns']) }}">No data found for the selected filters.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endif

</div>
