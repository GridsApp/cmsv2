<?php
 
namespace twa\cmsv2\Jobs;
 
use App\Models\Podcast;
use App\Services\AudioProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ReportJob implements ShouldQueue
{
    use Queueable;
 
    /**
     * Create a new job instance.
     */

     public $classPath;
     public $storagePath;

     public $filters;

    public function __construct($classPath ,$filters ,  $storagePath) {

        $this->classPath =  $classPath;
        $this->storagePath =  $storagePath;
        $this->filters =  $filters;
   

    }
 
    public function handle(): void
    {

        $class = (new $this->classPath);
        // dd($class);
        $class->setFilterResults($this->filters);
        
        $class->header();
        $columns = $class->columns;
        $rows = $class->rows();
        $footer = $class->footer;

        $result = [
            'columns' => $columns ,
            'footer' => $footer ,
            'rows' => $rows,
            'filters' => $this->filters,
            'created_at' => now()
        ];

        if(is_array($result)){
            $result = json_encode($result);
        }
      
        try {
            Storage::disk('local')->put($this->storagePath , $result);
        } catch (\Throwable $th) {
           
        }





    }
}