<?php


namespace twa\cmsv2\Livewire\Reports;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use twa\cmsv2\Jobs\ReportJob;

class ReportData extends Component
{

    public $slug;
    public $filters = [];
    public $classPath;
    public $storagePath;
    public $noData = true;
    public $data = [];
    public $isLoading = false;
    public $cms_user_id = null;

    public function mount()
    {
        $classes = get_report_classes();
        $this->classPath = $classes[$this->slug];
        $this->cms_user_id = session('cms_user')->id;
    }


    #[On('start-report')]
    public function updateFilters($filters)
    {

        $this->noData = true;
        $this->isLoading = true;
        $this->filters = $filters;      

        $fileName = now()->format('d-m-Y__h:i:s');

        $this->storagePath =  "/reports/$this->slug/$this->cms_user_id/$fileName.json";

        dispatch(new ReportJob($this->classPath , $filters , $this->storagePath));
    }

    public function getData(){
       
        if($this->storagePath && Storage::disk('local')->exists($this->storagePath)){
            $this->isLoading = false;
            $this->data = json_decode(Storage::disk('local')->get($this->storagePath) , 1);
           
            $this->noData =false;
            $this->isLoading = false;

            $this->dispatch('query-completed');

        }

    }


    public function render()
    {
      
        $class = new $this->classPath;

        return view('CMSView::components.reports.report-data', ['class' => $class ]);
    }
}
