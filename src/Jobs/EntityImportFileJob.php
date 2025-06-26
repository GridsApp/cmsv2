<?php

namespace twa\cmsv2\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class EntityImportFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $entity;
    protected $path;

    public function __construct($entity, $path)
    {
        $this->entity = $entity;
        $this->path = $path;
    }

    public function handle()
    {

        ini_set('memory_limit',-1);
        ini_set('max_execution_time', 3000);
        $path = storage_path("app/".$this->path);
        $entity = $this->entity;
    

  
        $import_fields = $entity->importColumns()->toArray();
        $field_primary = $entity->import_primary_column;
        // $path = public_path('coupons/batch_12_06_2025.csv');

        if (!file_exists($path) || !is_readable($path)) {
            dd("CSV file not found or not readable.");
        }

        $header = null;
        $data = [];

      

        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                } else {

                   $header =  collect($header)->map(function($header_name){
                        return str($header_name)->replace(' ' , '_')->toString();
                    })->toArray();

                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

     

        foreach ($data as $row) {

            
            $query = DB::table($entity->tableName)
                ->whereNull('deleted_at')
                ->when($field_primary && isset($row[$field_primary]), function ($q) use ($field_primary, $row) {
                    return $q->where($field_primary, $row[$field_primary]);
                });

            $query = $entity->importConditions($query);


            $found = $query->first();

            foreach ($import_fields as $import_field) {
                if (isset($row[$import_field])) {
                    $json[$import_field] = $row[$import_field];
                }
            }


            if (!$found) {
                DB::table($entity->tableName)->insert([
                    ...$json,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } elseif ($found && $field_primary && isset($row[$field_primary])) {

                DB::table($entity->tableName)->where($field_primary, $row[$field_primary])
                    ->update([
                        ...$json,
                        'updated_at' => now()
                    ]);
            }
        }
    }
}
