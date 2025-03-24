<?php

namespace twa\cmsv2\Entities;
use Database\Seeders\CMSUserSeeder;

class CmsUsers extends Entity
{

    public $entity = "CMS Users";
    public $tableName = "cms_users";
    public $slug = "cms-users";
    public $seeder = CMSUserSeeder::class;
    public $params = [
        'pagination' => 20,
    ];

    public function fields(){

        $this->addField("name" , ["container" => 'col-span-7' , 'required' => true]);
        $this->addField("email" , ["container" => 'col-span-7' , 'required' => true]);
        $this->addField("password" , ["container" => 'col-span-7' , 'required' => false]);

        return $this->fields;
    }

    public function columns(){

        $this->addColumn("name");
        $this->addColumn("email");

        return $this->columns;
    }

}
