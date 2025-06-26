<?php

namespace twa\cmsv2\Entities;

use Illuminate\Support\Facades\Route;
use TallStackUi\View\Components\Boolean;

class Entity
{

    public $attributes;
    public $fields;
    public $columns;
    public $import_columns;
    public $import_primary_column = null;
    public $slug = "";
    public $params = [];
    public $render = null;
    public $form = null;
    public $gridRules = [];

    public $row_operations = [];
    public $table_operations = [];

    public $filters = [];
    public $conditions = [];

    public $enableSorting = false;
    public $sortingCardLabel = null;



    public function __construct()
    {
        $this->fields = collect([]);
        $this->columns = collect([]);
        $this->attributes = collect([]);
        $this->import_columns = collect([]);

        $this->addColumn('id');
        $this->setRowOperations();

        $this->setTableOperations();
        $this->getPermissions();
    }

    public function getPermissions()
    {
        $base_permissions = ['create', 'show', 'edit', 'delete'];


        $permissions = [];

        foreach ($base_permissions as $action => $label) {


            $permissions[] = [
                'key' => "{$label}-{$this->slug}",
                'label' => ucwords(strtolower($label))

            ];
        }

        return $permissions;
    }

    // public function getPermissions(){
    //     return [
    //         'create' , 'show' , 'edit' , 'delete'
    //     ];
    // }


    public function setRowOperation($label, $link, $icon)
    {

        $this->row_operations[] =
            [
                'label' => $label,
                'link' => $link,
                'icon' => $icon,
            ];
    }
    public function setTableOperation($label, $link, $icon)
    {
        $this->table_operations[] =
            [
                'label' => $label,
                'link' => $link,
                'icon' => $icon,
            ];
    }

    public function setRowOperations()
    {
        if (cms_check_permission("edit-" . $this->slug)) {

            $edit_route = "/" . Route::getRoutes()->getByName('entity.update')->uri();

            $this->setRowOperation("Edit",  str_replace('{slug}', $this->slug, $edit_route),  '<i class="fa-solid fa-edit"></i>');
        }
    }


    public function setTableOperations()
    {
        if (cms_check_permission("create-" . $this->slug)) {
            $this->setTableOperation("Add New Record",  route('entity.create', ['slug' => $this->slug]),  '<i class="fa-solid fa-plus"></i>');
        }
        // Add import operation
        if (cms_check_permission("import-" . $this->slug)) {
            $this->setTableOperation("Import", route('entity.import', ['slug' => $this->slug]), '<i class="fa-solid fa-file-import"></i>');
        }
    }

    public function fields()
    {
        return $this->fields;
    }

    public function columns()
    {
        return $this->columns;
    }

    public function attributes()
    {
        return $this->attributes;
    }

    // public function filters(){
    //     return $this->filters;
    // }


    public function addColumn($field, $params = [], bool $filterable = false)
    {
        $field = config('fields.' . $field);


        if (!$field) {
            return $this;
        }



        $this->columns->push([
            ...$field,
            ...$params,
            'filterable' => $filterable
        ]);

        return $this;
    }


    public function addImportColumn($field , $primary = false){
        $field = config('fields.' . $field);

        if (!$field) {
            return $this;
        }

        $this->import_columns->push($field['name']);
        
        if($primary){
            $this->import_primary_column  = $field['name'];
        }

        return $this;
    }



    public function importConditions($query){

        return $query;
    }

    public function addField($field, $params = [])
    {


        $field = config('fields.' . $field);

        if (!$field) {
            return $this;
        }

        $this->fields->push([
            ...$field,
            ...$params,
            'index' =>  count($this->fields) + 1
        ]);

        return $this;
    }
    public function addAttribute($field, $params = [])
    {

        if (is_string($field)) {
            $field = config('fields.' . $field);
        }

        if (!$field) {
            return $this;
        }

        $this->attributes->push([
            ...$field,
            ...$params,
            'index' =>  count($this->fields) + 1
        ]);

        return $this;
    }


    public function submitCallback($thisLivewire)
    {


        return true;
    }
}
