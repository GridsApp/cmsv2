<?php

namespace twa\cmsv2\Traits;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;


use twa\cmsv2\Traits\ToastTrait;

trait FormTrait {

    use ToastTrait;

    public $slug;
    public $entity;

//    #[Url]
    public $form;

    public $fields;
    public $unique_id;
    public $id;
    public $data;


    public function setFieldValue($field , $data = null){
        $this->form[$field['name']] = field_init($field, $data);       
    }

    public function resetForm($data = null)
    {
        

        $form = request()->input("form");

        foreach ($this->fields as $info) {
            if (!isset($info['name'])) {
                continue;
            }

            if(isset($info['translatable']) && $info['translatable']){
                foreach(config('languages') as $language){
                    $field = [...$info];

                    if($form){
                        $data[$field['name'].'_'.$language['prefix']] =  $form[$field['name'].'_'.$language['prefix']];
                        $data = (object) $data;
                    }

                   
                    $field['translatable'] = false;
                    $field['name'] =  $field['name'].'_'.$language["prefix"];
                   
                    $this->setFieldValue($field , $data);
                }
            }else{
                if($form){
                    $data[$info['name']] =  $form[$info['name']];

                    $data = (object) $data;
                }
                $this->setFieldValue($info , $data);
            }

        }
    }

    public function mount()
    {

        $currentClass = get_entity($this->slug);
        if (!$currentClass) { abort(404); }
        $this->entity = [
            'title' => $currentClass->entity,
            'table' => $currentClass->tableName,
            'slug' => $currentClass->slug,
            'render' => $currentClass->formRender ?? null,
            ...$currentClass->params
        ];

        $this->fields = $currentClass->fields();

        if(!$this->fields){
            $this->fields = collect([]);
        }

        if ($this->id) {
            $this->data = DB::table($this->entity["table"])->find($this->id);
        }


        $this->resetForm($this->data);

    

    }

    public function render()
    {

        return view($this->entity["render"] ? $this->entity["render"]  : 'pages.form.components.default');
    }

    public function save()
    {

 
       

        $required_array = [];
        $required_messages = [];

        

        foreach($this->fields->where('required' , true) as $required){
          
            if(isset($required['translatable']) && $required['translatable']){
                $required_field = [...$required];
                foreach(config('languages') as $language){
                    $required['name'] = $required_field['name'].'_'.$language['prefix'];
                    $required_array [get_field_modal($required)] = 'required';
                    $required_messages [get_field_modal($required)] = str($required['label'] .' '. $language['language'])->lower();

                }
            }else{
                $required_array [get_field_modal($required)] = 'required';
                $required_messages [get_field_modal($required)] = str($required['label'])->lower();
            }
        }

        
        if(count($required_array) > 0){
            $this->validate($required_array , [] , $required_messages);
        }

        $array = [];
        foreach ($this->fields as $info) {


            if(isset($info['translatable']) && $info['translatable']){
                $field = [...$info];
                $field['translatable'] = false;
                
                foreach(config('languages') as $language){
                    $info['name'] = $field['name'].'_'.$language['prefix'];
                    
                    $array[$info['name']] = field_value($info, $this->form  );
                }
            }else{
                $array[$info['name']] = field_value($info, $this->form );
            }

            if((new $info["type"]($info)) instanceof \twa\cmsv2\Entities\FieldTypes\Password && !$array[$info['name']] ){
                unset($array[$info['name']]);
            }
        }
       

        if (!$this->unique_id) {

        }

        if ($this->entity['onsubmit'] ?? null) {
            foreach ($this->entity['onsubmit'] as $event) {
                $array[$event['name']] = str_replace("{value}", $array[$event['target']] ?? "", $event['value']);
            }
        }

        if ($this->id) {
            $array['updated_at'] = now();
            DB::table($this->entity['table'])->where('id', $this->id)->update($array);
            $id = $this->id;
        } else {
            $array['created_at'] = now();
            $array['updated_at'] = $array['created_at'];



            $id = DB::table($this->entity['table'])->insertGetId($array);


            if ($this->entity['auto_create'] ?? null) {


                if ($config = config('entities.' . $this->entity['auto_create']['entity'])) {

                    $values = collect($this->entity['auto_create']['values'])->map(function ($item) use ($id) {
                        return str_replace("{id}", $id, $item);
                    })->toArray();

                    DB::table($config['table'])->insertGetId($values);
                }
            }
        }


        $this->id = null;
        $this->data = null;

        $this->resetForm();



        try {
            $currentClass = get_entity($this->slug);
            $currentClass->callback($id);
        } catch (\Throwable $th) {
          
        }


        if ($this->unique_id) {
            $this->dispatch('record-created-' . $this->unique_id, id: $id);
            $this->sendSuccess("Created", "Record successfully created");
            $this->render();
        } else {

            $this->redirect(route('entity', ['slug' => $this->entity['slug']]));
        }
    }


}
