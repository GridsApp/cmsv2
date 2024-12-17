<?php

namespace twa\cmsv2\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class DefaultServiceProvider extends ServiceProvider{

    
    public function boot(){
        
        Livewire::component('entity-forms.form' , \twa\cmsv2\Livewire\EntityForms\Form::class);
        Livewire::component('entity-components.table' , \twa\cmsv2\Livewire\EntityComponents\Table::class);
        Livewire::component('entity-forms.login-form' , \twa\cmsv2\Livewire\EntityForms\LoginForm::class);
        Livewire::component('entity-forms.settings-form' , \twa\cmsv2\Livewire\EntityForms\SettingsForm::class);
        Livewire::component('elements.colorpicker' , \twa\cmsv2\Livewire\Elements\Colorpicker::class);
        Livewire::component('elements.date' , \twa\cmsv2\Livewire\Elements\Date::class);
        Livewire::component('elements.datetime' , \twa\cmsv2\Livewire\Elements\Datetime::class);
        Livewire::component('elements.editor' , \twa\cmsv2\Livewire\Elements\Editor::class);
        Livewire::component('elements.email' , \twa\cmsv2\Livewire\Elements\Email::class);
        Livewire::component('elements.file-upload' , \twa\cmsv2\Livewire\Elements\FileUpload::class);
        Livewire::component('elements.hidden' , \twa\cmsv2\Livewire\Elements\Hidden::class);
        Livewire::component('elements.language' , \twa\cmsv2\Livewire\Elements\Language::class);
        Livewire::component('elements.number' , \twa\cmsv2\Livewire\Elements\Number::class);
        Livewire::component('elements.password' , \twa\cmsv2\Livewire\Elements\Password::class);
        Livewire::component('elements.select' , \twa\cmsv2\Livewire\Elements\Select::class);
        Livewire::component('elements.textarea' , \twa\cmsv2\Livewire\Elements\Textarea::class);
        Livewire::component('elements.textfield' , \twa\cmsv2\Livewire\Elements\Textfield::class);
        Livewire::component('elements.toggle' , \twa\cmsv2\Livewire\Elements\Toggle::class);
         Livewire::component('modals.modal', \twa\cmsv2\Livewire\Modals\Modal::class);

    }

    public function register(){

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views/' , 'CMSView');
    }

}