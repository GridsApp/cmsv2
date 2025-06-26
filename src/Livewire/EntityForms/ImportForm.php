<?php

namespace twa\cmsv2\Livewire\EntityForms;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use twa\cmsv2\Traits\FormTrait;

class ImportForm extends Component
{
    use FormTrait;
    use WithFileUploads;


    public $identifier = null;

    public $import_file;
    public $slug;
    public $entityData = [];
    public $entity; // ✅ This will hold the actual entity object

    public $status = [
        'state' => null,
        'progress' => 0,
        'message' => null,
    ];
    
    public function mount($slug = null)
    {
        $entity = get_entity($slug);
        // Only store the fields you need, as an array
        $this->entityData = [
            // 'entity' => $entity,
            'slug' => $entity->slug,
            'tableName' => $entity->tableName,
            // ...add any other scalar/array fields you need
        ];
    }

    public function import()
    {
        $this->validate([
            'import_file' => 'required|file|mimes:csv',
        ]);

        $entity = get_entity($this->slug);

        // dd($entity);
        $entityName = $this->entityData['slug'] ?? $this->slug;
        $timestamp = now()->format('Ymd_His');
        $filename = "batch_{$timestamp}.csv";
        $folder = "entity_imports/{$entityName}";
        $path = $this->import_file->storeAs($folder, $filename);

        // dd($this->entityData);
        $this->identifier = md5($path);
        dispatch(new \twa\cmsv2\Jobs\EntityImportFileJob($entity, $path));
    }

    public function pollStatus()
    {
        if ($this->identifier) {
            $this->status = cache($this->identifier, [
                'state' => null,
                'progress' => 0,
                'message' => null,
            ]);
        }
    }

    public function render()
    {
        return view('CMSView::pages.form.components.import-form');
    }


}
