<?php

namespace twa\cmsv2\Http\Controllers;

use App\Models\CmsPushNotificationTemplate;
use App\Models\CmsSentPushNotification;
use Illuminate\Http\Request;
use twa\cmsv2\Traits\APITrait;
use twa\uikit\Traits\ToastTrait;

class NotificationController extends Controller
{


    use APITrait, ToastTrait;

    public function list()
    {
       
        $notifications = CmsSentPushNotification::whereNull('deleted_at')->get()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'text' => $notification->text,
                'image' => get_image($notification->image),
                'created_at' => $notification->created_at,
            ];
        });

        return $this->responseData($notifications);
    }
    public function sendNotification($id)
    {
       
        $cms_push_notification_template = CmsPushNotificationTemplate::whereNull('deleted_at')
            ->where('id', $id)
            ->first();
    
       
        if (!$cms_push_notification_template) {
            abort(404);
        }
    

        $notification = new CmsSentPushNotification;
        $notification->title_en = $cms_push_notification_template->title_en;
        $notification->title_ar = $cms_push_notification_template->title_ar;
        $notification->message_en = $cms_push_notification_template->message_en;
        $notification->message_ar = $cms_push_notification_template->message_ar;
        $notification->text_en = $cms_push_notification_template->text_en;
        $notification->text_ar = $cms_push_notification_template->text_ar;
        $notification->image = $cms_push_notification_template->image;
    
        $notification->save();
       

        $image_url = get_image($cms_push_notification_template->image);

        $conditions = [
            "condition" => [],
            "value" => []
        ];


        $titles = [
            'en' => $notification->title_en,
            'ar' => $notification->title_ar
        ];
        $messages = [
            'en' => $notification->message_en,
            'ar' => $notification->message_ar
        ];
        $data = [];

        (new \twa\omnipush\facades\OmniPushFacade("onesignal"))->sendPush($titles,$messages,$conditions , $data , $image_url);

        return redirect('/cms/cms-push-notification-templates');
       
    }
    
}
