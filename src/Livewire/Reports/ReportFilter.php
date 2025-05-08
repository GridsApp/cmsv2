<?php


namespace twa\cmsv2\Livewire\Reports;

use Livewire\Attributes\Url;
use Livewire\Component;
use twa\cmsv2\Traits\FormTrait;

class ReportFilter extends Component
{

    public $slug;

    #[Url]
    public $form = [];

    public $rows = [];

    public $classPath = null;


    public function mount()
    {
        $classes = get_report_classes();
        $this->classPath = $classes[$this->slug];
    
        $class = new $this->classPath;

        $form = [];
     
        foreach($class->filters as $filter){
     
            $form[$filter["name"]] = field_init($filter);
        }
    }

    public function render()
    {     
        $class = new $this->classPath;

        return view('CMSView::components.reports.report-filter' , ['class' => $class , 'rows' => []]);
    }


    public function applyFilters(){




        $class = new $this->classPath;

        $required_array = [];
        $required_messages = [];

        $all_required = collect($class->filters)->where('required' , true);
      
        foreach($all_required as $required){
            $required_array [get_field_modal($required)] = 'required';
            $required_messages [get_field_modal($required)] = str($required['label'])->lower();
        }

        if(count($required_array) > 0){
            $this->validate($required_array , [] , $required_messages);
        }

        // return redirect()->route('show-report', $query);
        $query = array_merge(['slug' => $this->slug], ['refine' => 1], $this->form);
        // return redirect()->route('show-report', $query);
        return $this->dispatch('render-report-data' , form: $query);
        return $this->render();
        // $this->dispatch('start-report' , $this->form);    
    }

//     public function applyFilters()
// {
//     $query = array_merge(['slug' => $this->slug], $this->form);

//     return redirect()->route('show-report', $query); // Use your route name and pass filters as query params
// }


}
