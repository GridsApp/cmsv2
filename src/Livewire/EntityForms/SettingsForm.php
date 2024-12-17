<?php

namespace twa\cmsv2\Livewire\EntityForms;


// use App\Traits\FormTrait;
// use App\Traits\ToastTrait;

use Livewire\Component;
use twa\cmsv2\Traits\FormTrait;
use twa\cmsv2\Traits\ToastTrait;

class SettingsForm extends Component
{
    use FormTrait , ToastTrait;



    public $group_settings = [];


    public function mount(){

        // dd(config('settings'));
        $this->group_settings = collect(config('settings'))->groupBy('group');
       
       
       
        $data = [];
        $this->fields = (collect(config('settings')))->map(function($config) use(&$data){
           
            $field = $config['field'];
            $info = config('fields.'.$field);
            if(isset($info['translatable']) && $info['translatable']){
                foreach(config('languages') as $language){
                    $data[$field.'_'.$language['prefix']] = $config['value'.'_'.$language['prefix']] ?? null;    
                }
            }else{
               
                $data[$field] = $config['value'];
            }
           
            
            return config('fields.'.$field);
        })->filter()->values()->toArray();

 
        $this->resetForm((object)$data);

       
    }

 
    public function handleSubmit(){

     
    
        $config = collect(config('settings'))->map(function($config_item){
            $info = config('fields.'.$config_item['field']);

           
       
            if(isset($info['translatable']) && $info['translatable']){
                $updated_info = [...$info];
                foreach(config('languages') as $language){
                    $updated_info['name'] = $info['name'].'_'.$language['prefix']; 
                    $config_item['value'.'_'.$language['prefix']] = $updated_info ? field_value($updated_info , $this->form) : $config_item['value'.'_'.$language['prefix']];
                }
                $config_item['translatable']= true;
               
               

            }else{
                $config_item['translatable']= false;
                $config_item['value'] = $info ? field_value($info , $this->form) : $config_item['value'];

            }

            return $config_item;
        })->toArray();





        $configContent = "<?php\n\nreturn " . var_export($config, true) . ";\n";
        file_put_contents(__DIR__ . '/../../../config/settings.php', $configContent);


        $this->sendSuccess("Settings Updated", "Settings successfully updated");
        $this->render();

    }


    public function render()
    {
        return view('CMSView::pages.form.components.settings-form');
    }

}
