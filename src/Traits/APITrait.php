<?php

namespace twa\cmsv2\Traits;

use vendor\twa\cmsv2\Classes\Notification;

trait APITrait {

    public function getRequest()
    {
        $params= request()->all();
        unset($params['user']);
        return [
            'endpoint' => request()->url(),
            'params' => $params,
            'headers' => [
                 'access-token' => request()->header('access-token'),
                 'language' => request()->header('language')
            ]
        ];
    }


    public function validateRequiredFields($request , $required){

        $fields = [];


        foreach($required as $required_key){

            $value = $request[$required_key] ?? null;

            if($value == null ||  $value == "" || empty($value)){
                $fields [] = $required_key;  
            }

        }

        if(count($fields) == 0){
            return null;
        }

        $fields = implode("," , $fields);

        return notification()->error("Missing Fields","Fields are required: ". $fields);
    }

    public function validationErrors($validator)
    {

        $errors = [];
        foreach(collect($validator->errors()) as $key => $err){
           if($err[0] ?? null){
               $errors[$key] = $err[0]; 
           }
        }

       return $errors;
    }

    public function  responseValidation($validator , Notification $notification = null)
    {

        $request = $this->getRequest();
   
        $response = [];
        

        if($notification){
            $response['notification'] = [
                'type' => 'validation',
                'title' => $notification->title,
                'message' => $notification->message
            ];
        }else{
            $response['notification'] = [
                'type' => 'validation'
            ];
        }

        $response["data"] = $this->validationErrors($validator);

        return response()->json([
            'response' => $response,
            'request' => $request
        ] , 400);


    }

    public function responseData( $data = null , Notification $notification = null )
    {

        $request = $this->getRequest();
   
        $response = [];
         
        if($notification){
            $response['notification'] = [
                'type' => $notification->type,
                'title' => $notification->title,
                'message' => $notification->message
            ];
        }

        $response["data"] = $data;

        return response()->json([
            'response' => $response,
            'request' => $request
        ] , ($notification->type ?? null) == "error" ? 400 : 200);
    }

    public function response(Notification $notification)
    {
        $request = $this->getRequest();
   
        $response = [];
         
        $response['notification'] = [
            'type' => $notification->type,
            'title' => $notification->title,
            'message' => $notification->message
        ];

        return response()->json([
            'response' => $response,
            'request' => $request
        ] , ($notification->type ?? null) == "error" ? 400 : 200);
    }

}
