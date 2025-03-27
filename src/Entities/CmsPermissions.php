<?php

namespace twa\cmsv2\Entities;


class CmsPermissions extends Entity
{

    public $entity = "CMS Permissions";
    public $tableName = "cms_permissions";
    public $slug = "cms-permissions";

    public $params = [
        'pagination' => 20,
    ];

    public function fields(){

        $this->addField("label" , ["container" => 'col-span-7' , 'required' => true]);
        $this->addField("key" , ["container" => 'col-span-7' , 'required' => true]);
        $this->addField("menu_type" , ["container" => 'col-span-7' , 'required' => true]);
  
        return $this->fields;
    }

    public function columns(){

        $this->addColumn("label");
      
        return $this->columns;
    }

}
