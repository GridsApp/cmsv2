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
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use twa\cmsv2\Reports\Exports\ReportExport;
use Livewire\WithPagination;

class ReportData extends Component
{

    use WithPagination;

    public $slug;
    public $filters = [];
    public $classPath;
    public $storagePath;
    public $noData = true;

    public $isLoading = false;
    public $cms_user_id = null;

    public $pagination = null;

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
        ini_set('max_excecution_time',3000);

        $classes = get_report_classes();
        $this->classPath = $classes[$this->slug];
        $class = (new $this->classPath);


        $class->setFilterResults($this->filters);
        $class->header();
        $columns = $class->columns;
        $rows = $class->rows();
      

        $footer = $class->footer;

        return [
            'columns' => $columns ,
            'footer' => $footer ,
            'rows' => $rows,
            'filters' => $this->filters,
            'created_at' => now()
        ];

    }
    


    
    public function exportData()
    {

        ini_set('memory_limit',-1);
        ini_set('max_execution_time',3000);

        $classes = get_report_classes();
        $this->classPath = $classes[$this->slug];
        $class = (new $this->classPath);


        $class->setFilterResults($this->filters);
        $class->header();
        $columns = $class->columns;
        $class->removePagination();
        $rows = $class->rows();
        $footer = $class->footer;

        $data =  [
            'columns' => $columns ,
            'footer' => $footer ,
            'rows' => $rows,
            'filters' => $this->filters,
            'created_at' => now()
        ];
        // dd($data);


     
        $filtered_columns = collect($data['columns'])->map(function ($col) {
            $col['label'] = strip_tags(preg_replace('/<br\s*\/?>/', ' ', $col['label']));
            return $col;
        })->toArray();
    
      
        $rows = collect($data['rows'])->map(function ($row) use ($data) {
            return array_map(function ($col) use ($row , $data) {
                return $row[$col['name']] ?? '';
            }, collect($data['columns'])->toArray()); 
        })->toArray();
    
      
        $footer = [];
        if (isset($data['footer'])) {
            $footer = collect($data['columns'])->map(function ($column) use ($data) {
                return $data['footer'][$column['name']] ?? '';
            })->toArray();
            $rows[] = $footer;
        }
    
      
        $filterText = $class->getExportFileName($this->slug, $this->filters);

        $fileName = "{$filterText}.csv";
    

        $this->skipRender();


        return $this->exportTheData($rows, $filtered_columns ,$fileName);
        // return Excel::download(new ReportExport($rows, $filtered_columns), $fileName);
    }
    

    public function exportTheData($rows , $columns , $fileName){



   
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ];
 
        $callback = function () use ($rows, $columns){


     
            $handle = fopen('php://output', 'w');
 
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, collect($columns)->pluck('label')->toArray());
 
    
                foreach ($rows as $row) {
                    // dd($row);
                    fputcsv($handle, $row);
                }
       
 
            fclose($handle);
        };
 
        return Response::stream($callback, 200, $headers);

    }

 

    public function render()
    {

        $data = [];

        if(($this->filters['refine'] ?? 0) == 1 ){
    
           $data =  $this->loadData();
        }


        $classes = get_report_classes();
        $this->classPath = $classes[$this->slug];
        $class = (new $this->classPath);


        return view('CMSView::components.reports.report-data', ['class' => $class,'data' => $data ]);
    }
}
