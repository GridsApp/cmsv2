<?php

namespace twa\cmsv2\Entities;
use Database\Seeders\CMSUserSeeder;
use Illuminate\Support\Facades\Route;

class CmsUserRoles extends Entity
{

    public $entity = "CMS User Roles";
    public $tableName = "cms_user_roles";
    public $slug = "cms-user-roles";
    public $params = [
        'pagination' => 20,
    ];

    public function fields(){

        $this->addField("label" , ["container" => 'col-span-7' , 'required' => true]);
  
        return $this->fields;
    }


    public function setRowOperations(){

        $edit_route = "/".Route::getRoutes()->getByName('entity.update')->uri();

        $route = "/".Route::getRoutes()->getByName('cms-set-permissions')->uri();
        $this->setRowOperation("Set Permissions" , $route , '<i class="fa-solid fa-eye"></i>');
    

        $this->setRowOperation("Edit" ,  str_replace('{slug}' , $this->slug , $edit_route),  '<i class="fa-solid fa-edit"></i>');
    }

    public function columns(){

        $this->addColumn("label");
      
        return $this->columns;
    }

}
