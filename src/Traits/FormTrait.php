<?php

namespace twa\cmsv2\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Url;


use twa\uikit\Traits\ToastTrait;

trait FormTrait {

    use ToastTrait;

    public $slug;
    public $entity;

//    #[Url]
    public $form;
    public $attributes_form;

    public $fields;
    public $custom_attributes;
    public $unique_id;
    public $id;
    public $data;


    public function setFieldValue($field , $data = null){
        
        $this->form[$field['name']] = field_init($field, $data);
    }

    public function setAttrValue($field , $data = null){
        $this->attributes_form[$field['name']] = field_init($field, $data);
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

        if($data->attributes ?? null) {
            $attr_data = json_decode($data->attributes);
        }else{
            $attr_data = null;
        }

        foreach($this->custom_attributes ?? [] as $attr_info){
            if (!isset($attr_info['name'])) {
                continue;
            }


            if(isset($attr_info['translatable']) && $attr_info['translatable']){




                foreach(config('languages') as $language){

                    $field = [...$attr_info];
                    $field['translatable'] = false;
                    $field['name'] =  $field['name'].'_'.$language["prefix"];

                    $this->setAttrValue($field , $attr_data);
                }
            }else{


                $this->setAttrValue($attr_info , $attr_data);
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




        $this->custom_attributes = $currentClass->attributes()->map(function($attr_info){


            if($attr_info['livewire']['wire:model'] ?? null) {
                $attr_info['livewire']['wire:model'] = Str($attr_info['livewire']['wire:model'])->replace('form.', 'attributes_form.')->toString();
            }

            if($attr_info['livewire']['wire:model.live'] ?? null) {
                $attr_info['livewire']['wire:model.live'] = Str($attr_info['livewire']['wire:model.live'])->replace('form.', 'attributes_form.')->toString();
            }

            return $attr_info;

        });




        if(!$this->fields){
            $this->fields = collect([]);
        }


        if(!$this->custom_attributes){
            $this->custom_attributes = collect([]);
        }

        if ($this->id) {
            $this->data = DB::table($this->entity["table"])->find($this->id);
        }


        $this->resetForm($this->data);

    }

    public function render()
    {

        return view($this->entity["render"] ? $this->entity["render"]  : 'CMSView::pages.form.components.default');
    }

    public function save()
    {


        $currentEntity = get_entity($this->entity['slug']);


        $result = $currentEntity->submitCallback($this);

        if ($result !== true) {
            return $result;
        }

        $required_array = [];
        $required_messages = [];



        $all_required_fields = $this->fields->where('required' , true);
        $all_required_attributes = $this->custom_attributes->where('required' , true);

        $all_required = collect()->merge($all_required_fields)->merge($all_required_attributes);


        foreach($all_required as $required){

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

            if((new $info["type"]($info)) instanceof \twa\uikit\FieldTypes\Password && !$array[$info['name']] ){
                unset($array[$info['name']]);
            }
        }

        $attributes_array = [];
        foreach ($this->custom_attributes as $info) {


            if(isset($info['translatable']) && $info['translatable']){
                $field = [...$info];
                $field['translatable'] = false;

                foreach(config('languages') as $language){
                    $info['name'] = $field['name'].'_'.$language['prefix'];

                    $attributes_array[$info['name']] = field_value($info, $this->attributes_form  );
                }
            }else{
                $attributes_array[$info['name']] = field_value($info, $this->attributes_form );
            }

            if((new $info["type"]($info)) instanceof \twa\cmsv2\Entities\FieldTypes\Password && !$attributes_array[$info['name']] ){
                unset($attributes_array[$info['name']]);
            }
        }


        if(Schema::hasColumn( $this->entity['table'],'attributes')){
            $array['attributes'] = json_encode($attributes_array);
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
            if(Schema::hasColumn( $this->entity['table'],'orders')){
                $array['orders'] = DB::table($this->entity['table'])->max('orders') + 1;
            }



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
