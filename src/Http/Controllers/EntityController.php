<?php

namespace twa\cmsv2\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Route;
use twa\uikit\Classes\ColumnOperationTypes\BelongsTo;
use twa\uikit\Classes\ColumnOperationTypes\FileUpload;
use twa\uikit\Classes\ColumnOperationTypes\ManyToMany;

class EntityController extends Controller
{
    public function render($slug)
    {

        $entity = get_entity($slug);



        // dd("here");

        $table =  (new \twa\uikit\Classes\Table\TableData($entity->entity, $entity->tableName));



        foreach ($entity->columns() as $column) {

            

        


            if (isset($column['label']) && isset($column['name']) && isset($column['type'])) {
                $label = $column['label'];
                $name = $column['name'];


                $column_type = $column['type'];
                $field = $column;
                $typeInstance = new $column_type($field);

                $type = $typeInstance->columnType();
                $operationType = $typeInstance->operationType();
                $instance = new $operationType(null, null, null);

                if ($instance instanceof ManyToMany) {
                    $table = $table->manyToMany($field['options']['table'], $field['name'], $field['options']['field'], $column['name'], []);
                }
                if ($instance instanceof BelongsTo) {
                    $table = $table->belongsTo($field['options']['table'], $field['name'],  false)
                        ->addColumn($label, $name,  $type, \twa\uikit\Classes\ColumnOperationTypes\DefaultOperationType::class, [$field['options']['table'] . '.' . $field['options']['field']]);
                    continue;
                }

                if($column['filterable']){

                    $filterType = $typeInstance->filterType();

                    $attributes = [
                      
                    ];

                    if($column['options']['table'] ?? null){
                        $attributes['table'] =  $column['options']['table'];
                        $attributes['foreign_key'] = $column['name'];
                        $attributes['column'] = $column['options']['field'];
                    }
                    
                    $table = $table->addFilter($label, $name, $name, $filterType , $attributes);
                }



                $table = $table->addColumn(
                    $label,
                    $name,
                    $type,
                    $operationType,
                    $name
                );
            }
        }

        // dd($entity);

    

        foreach($entity->row_operations as $row_operation){
          
            $table->addRowOperation(
               ...$row_operation
            );
        }

        foreach($entity->table_operations as $table_operation){
            $table->addTableOperation(
               ...$table_operation
            );
        }


        
        // dd($entity->conditions);

        $conditions = update_conditions($entity->conditions);

        foreach($conditions as $condition){
            $table->addCondition($condition['type'] , $condition['column'] , $condition['value'] , $condition['operand']);
        }


        // dd($entity->filters());
        // dd($conditions);

        // $table->addTableOperation(
        //     'Add New Record',
        //     route('entity.create', ['slug' => $slug]),
        //     '<i class="fa-solid fa-plus"></i>'
        // );
        
        // $edit_route = "/".Route::getRoutes()->getByName('entity.update')->uri();

      


    //    dd( route('entity.update', ['slug' => $slug , 'id' => '[id]']));

        $path = $entity->render ? $entity->render : 'CMSView::pages.entity.index';


  

  

        return view($path, ['table' => $table->get()]);
    }

    public function create($slug)
    {
        $entity = get_entity($slug);

        $path = $entity->form ? $entity->form : 'CMSView::pages.form.index';

        return view($path, ['slug' => $slug, 'id' => null]);
    }

    public function update($slug, $id)
    {
        $entity = get_entity($slug);
        $path = $entity->form ? $entity->form : 'CMSView::pages.form.index';

        return view($path, ['slug' => $slug, 'id' => $id]);
    }




    public function migrate()
    {

        $databaseName = env('DB_DATABASE');

        Process::run('php artisan migrate');

        foreach (config('entity-mapping') as $className) {

            $entity = new $className;

            $entity_fields =  $entity->fields();

            if (!Schema::hasTable($entity->tableName)) {

                Schema::create($entity->tableName, function (Blueprint $table) use ($entity, $entity_fields) {
                    $table->id();
                    $table->timestamps();
                    $table->softDeletes();
                });
            }


            Schema::table($entity->tableName, function (Blueprint $table) use ($entity, $entity_fields) {
                foreach ($entity_fields as $entity_field) {

                    $field = [...$entity_field];

                    if (isset($field['translatable']) && $field['translatable']) {
                        foreach (config('languages') as $language) {

                            $field['name'] = $entity_field['name'] . '_' . $language['prefix'];
                            $field['name'] = trim($field['name']);

                            if (Schema::hasColumn($entity->tableName, $field['name'])) {
                                continue;
                            }

                            (new $field['type']($field))->db($table);
                        }
                    } else {

                        $entity_field['name'] = trim($entity_field['name']);

                        if (Schema::hasColumn($entity->tableName, $entity_field['name'])) {
                            continue;
                        }

                        (new $entity_field['type']($entity_field))->db($table);
                    }
                }
            });


            if (property_exists($entity, 'seeder')) {
                (new $entity->seeder)->run();
            }
        }
    }
}
