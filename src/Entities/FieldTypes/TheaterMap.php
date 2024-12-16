<?php

namespace twa\cmsv2\Entities\FieldTypes;


class TheaterMap extends FieldType
{

    public function component()
    {
        return "components.theater-map";
    }


    public function db(&$table){
        $table->longText($this->field['name'])->nullable();
    }

    public function initalValue($data)
    {
        return json_decode($data->{$this->field['name']} ?? '[]');
    }

    public function value($form)
    {
        if($form[$this->field['name']] ?? null){
            return json_encode($form[$this->field['name']]);
        }else{
            return "[]";
        }
    }

    public function display($data){
        return null;
    }

}
