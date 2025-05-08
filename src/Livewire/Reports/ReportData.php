<?php


namespace twa\cmsv2\Livewire\Reports;


use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use twa\cmsv2\Jobs\ReportJob;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use twa\cmsv2\Reports\Exports\ReportExport;

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


      
        
        $this->noData = true;
        $this->isLoading = false;
    }



    #[On('render-report-data')] 
    public function renderReportData($form){
        
        $this->data = null;
        $this->filters = $form;
        $this->render();
    }


    // #[On('start-report')]
    // public function updateFilters($filters)
    // {

    //     $this->noData = true;
    //     $this->isLoading = true;
    //     $this->filters = $filters;      

    //     $fileName = now()->format('d-m-Y__h:i:s');

    //     $this->storagePath =  "/reports/$this->slug/$this->cms_user_id/$fileName.json";

    //     dispatch(new ReportJob($this->classPath , $filters , $this->storagePath));
    // }

    // public function getData(){
       
    //     if($this->storagePath && Storage::disk('local')->exists($this->storagePath)){
    //         $this->isLoading = false;
    //         $this->data = json_decode(Storage::disk('local')->get($this->storagePath) , 1);
           
    //         $this->noData =false;
    //         $this->isLoading = false;

    //         $this->dispatch('query-completed');

    //     }

    // }

    public function loadData()
    {


        ini_set('memory_limit',-1);
        ini_set('max_excecution_time',300);

        $class = (new $this->classPath);


        $class->setFilterResults($this->filters);
        $class->header();
        $columns = $class->columns;
        $rows = $class->rows();

        $footer = $class->footer;

        $this->data =  [
            'columns' => $columns ,
            'footer' => $footer ,
            'rows' => $rows,
            'filters' => $this->filters,
            'created_at' => now()
        ];

    }
    


    
    public function exportData()
    {
        if (empty($this->data)) {
            return;
        }
    
     
        $filtered_columns = collect($this->data['columns'])->map(function ($col) {
            $col['label'] = strip_tags(preg_replace('/<br\s*\/?>/', ' ', $col['label']));
            return $col;
        })->toArray();
    
      
        $rows = collect($this->data['rows'])->map(function ($row) {
            return array_map(function ($col) use ($row) {
                return $row[$col['name']] ?? '';
            }, collect($this->data['columns'])->toArray()); 
        })->toArray();
    
      
        $footer = [];
        if (isset($this->data['footer'])) {
            $footer = collect($this->data['columns'])->map(function ($column) {
                return $this->data['footer'][$column['name']] ?? '';
            })->toArray();
            $rows[] = $footer;
        }
    
       
        $filterText = $this->filters ? implode('_', array_map(function ($key, $value) {
            return "{$key}_{$value}";
        }, array_keys($this->filters), $this->filters)) : 'all';
    
    
        $fileName = "{$this->slug}_{$filterText}.xlsx";
    

        $this->skipRender();

        return Excel::download(new ReportExport($rows, $filtered_columns), $fileName);
    }
    

 

    public function render()
    {



     

        if(($this->filters['refine'] ?? 0) == 1 ){
       

            $this->loadData();
        }


        $class = new $this->classPath;


        return view('CMSView::components.reports.report-data', ['class' => $class,'data' => $this->data ]);
    }
}
