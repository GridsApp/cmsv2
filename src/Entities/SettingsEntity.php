<?php

namespace twa\cmsv2\Entities;
use twa\cmsv2\Entities\Entity;

class SettingsEntity extends Entity
{

    public $entity = "Settings";
    public $tableName = "settings";
    public $slug = "settings";


    public $render = "pages.entity.settings";

    public $params = [

        'pagination' => 20
    ];


    public function fields(){

        // $this->addField("movie" , ["container" => 'col-span-7', 'required' => true]);
        // $this->addField("date" , ["container" => 'col-span-7', 'required' => true]);
        // $this->addField("time" , ["container" => 'col-span-7', 'required' => true]);
        // $this->addField("end_time" , ["container" => 'col-span-7', 'required' => false]);


        
        // $this->addField("screen_type" , ["container" => 'col-span-7']);
        // $this->addField("theater" , ["container" => 'col-span-7', 'required' => true]);
        // $this->addField("group" , ["container" => 'col-span-7']);
        // $this->addField("color" , ["container" => 'col-span-7', 'required' => true]);
       
        // $this->addField("duration" , ["container" => 'col-span-7']);
        // $this->addField("visibility" , ["container" => 'col-span-7']);

        // $this->addField("system" , ["container" => 'col-span-7']);

        return $this->fields;
    }

    public function columns(){
        return $this->columns;
    }


}
