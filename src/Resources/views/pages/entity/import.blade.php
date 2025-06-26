@extends('CMSView::layouts.main')

@section('content')

{{-- <div class="container mx-auto max-w-lg py-10">
    <div class="bg-white shadow-lg rounded-lg p-8 border border-gray-200">
        <h2 class="text-2xl font-bold mb-4 text-center text-primary">Import {{ $entity->entity }}</h2>
        <p class="mb-6 text-gray-600 text-center text-[12px]">
            Upload a CSV file to import data into the system. Please ensure the file follows the required format and contains valid entries. Only appropriate and non-duplicate records will be processed.
        </p>
        <form action="{{ route('entity.import.post', ['slug' => $slug]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label for="import_file" class="block text-sm font-medium text-gray-700 mb-2">Select CSV File</label>
                <input type="file" name="import_file" id="import_file" accept=".csv" required >
            </div>
            <button type="submit" class="text-[12px] btn !border !border-gray-400">Import</button>
        </form>
      
    </div>
</div> --}}
<livewire:entity-forms.import-form   :slug="$slug"/>

@endsection
