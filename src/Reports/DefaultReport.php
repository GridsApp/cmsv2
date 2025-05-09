<?php

namespace twa\cmsv2\Reports;

class DefaultReport
{
    public $columns = [];
    public $filters = [];
    public $rows = [];
    public $footer = [];
    public $filterResults = [];
    public $pagination = null;

    protected $reportData = [];



    public function __construct()
    {

        $this->columns = collect([]);
        $this->filters = collect([]);


        $this->filters();
        $this->header();
        $this->footer();

        $this->rows = $this->rows();
    }

    public function removePagination(){
        $this->pagination = null;
    }

    public function setRow($data)
    {


        $row = [];

        foreach ($this->columns as $column) {
            $row[$column['name']] = $data[$column['name']] ?? "";
        }

        return $row;
    }


    public function addColumn($name, $label)
    {
        $this->columns[] = [
            'name' => $name,
            'label' => $label,
        ];
    }

    public $filterValues = [];

    public function applyFilters($values)
    {

        // dump("test 1");
        // dd($values);
        $this->filterValues = $values;
        // dd($this->filterValues[]);

    }


 
    public function addFilter($name)
    {

      
        if(is_string($name)){
   
            $field = config('fields.'.$name);
        }else{
            $field = $name;
        }

        if($field){
            $this->filters[] = $field;
        }

    }

    public function setFilterResults($filters){

        $this->filterResults = $filters;

    }


    public function setFooter($footer)
    {

        $this->footer = $footer;
    }


    public function header() {}


    public function filters() {}


    public function rows()
    {
        // dd($this->filterValues);
        return [];
    }
    public function query()
    {
        $this->reportData = [];
    }


    public function getRows()
    {
        
        return $this->rows;
    }

 
    public function getFilter($name)
    {
 
        // dump("test 2");

        // dump($this->filterValues);

        return $this->filterValues[$name] ?? null;
    }

    public function footer() {}
}
