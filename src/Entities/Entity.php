<?php

namespace twa\cmsv2\Entities;


class Entity
{

    public $fields;
    public $columns;
    public $slug = "";
    public $params = [];
    public $render = null;
    public $form = null;
    public $gridRules = [];

    public function __construct()
    {
        $this->fields = collect([]);
        $this->columns = collect([]);

        $this->addColumn('id');
    }




    public function fields(){
        return $this->fields;
    }

    public function columns(){
        return $this->columns;
    }


    public function addColumn($field , $params = []){
        $field = config('fields.'.$field);

        if(!$field){
            return $this;
        }

        $this->columns->push([
            ...$field,
            ...$params
        ]);

        return $this;
    }


    public function addField($field , $params = []){


        $field = config('fields.'.$field);

        if(!$field){
            return $this;
        }

        $this->fields->push([
            ...$field,
            ...$params,
            'index' =>  count($this->fields) + 1
        ]);

        return $this;
    }


    // public function callback($id){

    // }


}
