<?php

namespace twa\cmsv2\Entities;


class CmsUserRolePermission extends Entity
{

    public $entity = "CMS User Role Permission";
    public $tableName = "cms_user_role_permission";
    public $slug = "cms-user-role-permission";

    public $params = [
        'pagination' => 20,
    ];

    public function fields(){

        $this->addField("cms_user_role" , ["container" => 'col-span-7' , 'required' => true]);
        $this->addField("cms_permission" , ["container" => 'col-span-7' , 'required' => true]);
        $this->addField("menu_key" , ["container" => 'col-span-7' , 'required' => true]);
  
        return $this->fields;
    }

    public function columns(){

        $this->addColumn("label");
      
        return $this->columns;
    }

}
