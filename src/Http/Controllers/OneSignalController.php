<?php

namespace twa\cmsv2\Http\Controllers;


class OneSignalController
{

    public $data;

    public function __construct($data)
    {
        $required = ["AppID" , "AuthKey" ];
        $missed = collect(collect($required)->diff(collect($data)->keys()->toArray()));
        if($missed->count() > 0) {
            $response = ["location" => "OneSignalController", "status" => "error", "message" => "You have missed some or the required data in your config file (omnipush.php) to run the OneSignal API. The required data you missed are: " .$missed->implode(',') ];
            echo json_encode($response);
            exit();
        }
        $this->data = collect($data);
    }


    public function sendPush($titles,$notifications,$conditions = [],$data = [] , $image_url = null, $playerID = null){


            $heading = [];
            foreach ($titles AS $key => $title){
                $heading[$key] = $title;
            }
            $content = [];
            foreach ($notifications AS $key => $notification){
                $content[$key] = $notification;
            }

            
            $array = [];
            foreach ($conditions["condition"] ?? [] AS $key => $condition){
                $array[]=[ "key" =>  $conditions["condition"][$key], "relation" => "=", "value" => $conditions["value"][$key]  ];
            }

            $query = [];
            foreach ($array AS $key => $row){
                $query[]=$row;
                if($key != count($array) - 1){ $query[]=[ "operator" => "AND" ]; }
            }



            $fields = array(
                'app_id' => $this->data['AppID'],
                'tags' => $query,
                'data' => $data,
                'contents' => $content,
                'headings' => $heading,
                'priority' => 10,
            );

            if(!is_null($image_url)){
                $fields["ios_attachments"] =  array("id"=> $image_url);
                $fields["huawei_big_picture"] =  $image_url;
                $fields["big_picture"] =  $image_url;
                $fields["large_icon"] =  $image_url;
            }


            if($playerID){
                if(is_array($playerID)){
                    $fields['include_player_ids'] = $playerID;
                }else{
                    $fields['include_player_ids'] = [$playerID];
                }

            }
        $fields = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                'Authorization: Basic '.$this->data['AuthKey']));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = (curl_exec($ch));
            curl_close($ch);

            $response = json_decode($response,1);

            return $response;

        }
}
