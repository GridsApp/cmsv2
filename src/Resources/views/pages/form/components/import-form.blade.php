<div class="container mx-auto max-w-lg py-10">

    <div class="bg-white shadow-lg rounded-lg p-8 border border-gray-200">
        <h2 class="text-2xl font-bold mb-4 text-center text-primary">Import {{ $entity->entity ?? '' }}</h2>
        <p class="mb-6 text-gray-600 text-center text-[12px]">
            Upload a CSV file to import data into the system. Please ensure the file follows the required format and
            contains valid entries. Only appropriate and non-duplicate records will be processed.
        </p>

        <form wire:submit.prevent="import" class="space-y-6">
            <div>
                <label for="import_file" class="block text-sm font-medium text-gray-700 mb-2">Select CSV File</label>
                <input type="file" wire:model="import_file" id="import_file" accept=".csv" required>
                @error('import_file')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="text-[12px] btn !border !border-gray-400">Import</button>
        </form>

        @if ($identifier)
            <div wire:poll.2s="pollStatus" class="mt-6">
                @if ($status['state'] === 'starting')
                    <span class="text-yellow-600 flex items-center text-[12px]"><svg
                            class="animate-spin h-5 w-5 mr-2 text-yellow-600" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>Starting import...</span>
                @elseif($status['state'] === 'processing')
                    <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                        <div class="bg-blue-500 h-4 rounded-full" style="width: {{ $status['progress'] }}%"></div>
                    </div>
                    <div class="text-center text-sm text-gray-700">{{ $status['message'] }}</div>
                @elseif($status['state'] === 'completed')
                    <span class="text-green-600 text-[12px]">{{ $status['message'] }}</span>
                @elseif($status['state'] === 'failed')
                    <span class="text-red-600">{{ $status['message'] }}</span>
                @else
                    <span
                        class="text-[12px]">{{ $status['message'] ?? 'Reading cache for ' . $identifier . '...' }}</span>
                @endif
            </div>
        @endif
    </div>
</div>
