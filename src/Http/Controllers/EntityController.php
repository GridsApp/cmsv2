<?php

namespace twa\cmsv2\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Process;


class EntityController extends Controller
{
    public function render($slug)
    {

        $entity = get_entity($slug);
        $path = $entity->render ? $entity->render : 'CMSView::pages.entity.index';

        return view($path, ['slug' => $slug]);
    }

    public function create($slug)
    {
        $entity = get_entity($slug);

        $path = $entity->form ? $entity->form : 'CMSView::pages.form.index';

        return view($path, ['slug' => $slug , 'id' => null ]);
    }

    public function update($slug, $id)
    {
        $entity = get_entity($slug);
        $path = $entity->form ? $entity->form : 'CMSView::pages.form.index';

        return view($path, ['slug' => $slug , 'id' => $id ]);
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

                    if(isset($field['translatable']) && $field['translatable']){
                        foreach(config('languages') as $language){
                            
                            $field['name'] = $entity_field['name'].'_'.$language['prefix'];
                            $field['name'] = trim($field['name']);

                        if(Schema::hasColumn($entity->tableName, $field['name'])) {
                            continue;
                        }

                        (new $field['type']($field))->db($table);

                        }
                            

                        
                    }else{

                        $entity_field['name'] = trim($entity_field['name']);

                        if (Schema::hasColumn($entity->tableName, $entity_field['name'])) {
                            continue;
                        }

                        (new $entity_field['type']($entity_field))->db($table);

                        }
                }
            });


            if(property_exists($entity , 'seeder')){
                (new $entity->seeder)->run();
            }


        }
    }
}
