<?php

namespace twa\cmsv2\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function render(){
        $data = [];


        $filePath = config_path('settings.php');
        if (file_exists($filePath)) {
            return view("CMSView::pages.settings");
        }
      

        $configContent = "<?php\n\nreturn " . var_export($data, true) . ";\n";

        try {
            file_put_contents($filePath, $configContent);
        } catch (\Exception $e) {
           abort(400);
        }

        return view("CMSView::pages.settings");
    }

    public function getSetting($key , $locale = "en"){

        $setting = collect(config('settings'))->where('field' , $key)->first();

        if(!$setting){
             return null;
        }
    
       $info = config('fields.' . $setting['field']);

        if (!$info) {
            return null;
        }

        $info['name'] = $setting['translatable'] ?? false ? 'value_' . $locale : 'value';
        $value = (new $info['type']($info))->display($setting);

          
        return $value;
    }
}
