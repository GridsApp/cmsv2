<?php

namespace twa\cmsv2\Livewire\Elements;

use Livewire\Component;
use Livewire\Attributes\Modelable;
use Livewire\WithFileUploads;

class FileUpload extends Component
{

    use WithFileUploads;

    #[Modelable]
    public $value;

    public $file = [];
    public $info;


    


    public function render()
    {

        return view('CMSView::components.form.file-upload');
    }


    public function save(){
        dd($this->value);
    }

}
