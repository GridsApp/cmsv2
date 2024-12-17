<?php

namespace twa\cmsv2\Classes;

class Notification {


    public $notification;
    public $type;
    public $title;
    public $message;




    public function __construct($action = null)
    {
        if($action){
            $this->notification = collect(config('notifications'))->where('action' , $action)->first();
        }
    }

    public function setter($title , $message){
        $this->title = $title;
        $this->message = $message;
    }

    public function success($title = null , $message = null){
        $this->type = "success";
        if(!$this->notification){
            $this->setter($title , $message);
        }else{
            $this->setter($this->notification[$this->type]['title'] ?? null ,$this->notification[$this->type]['message'] ?? null);
        }
        return $this;
    }

    public function error($title = null , $message = null){
       
        $this->type = "error";
        if(!$this->notification){
            $this->setter($title , $message);
        }else{
            $this->setter($this->notification[$this->type]['title'] ?? null ,$this->notification[$this->type]['message'] ?? null);
        }

        return $this;
    }

}
