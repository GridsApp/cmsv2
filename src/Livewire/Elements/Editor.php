<?php

namespace twa\cmsv2\Livewire\Elements;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class Editor extends Component
{    
    #[Modelable]
    public $value;
    public $info;
    
    public function render()
    {
        return view('CMSView::components.form.editor');
    }
}
