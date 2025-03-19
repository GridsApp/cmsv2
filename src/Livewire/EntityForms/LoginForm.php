<?php

namespace twa\cmsv2\Livewire\EntityForms;


use twa\cmsv2\Models\CmsUser;
use twa\cmsv2\Traits\FormTrait;
use twa\uikit\Traits\ToastTrait;
use Livewire\Component;

class LoginForm extends Component
{
    use FormTrait , ToastTrait;


    public function mount(){

        $this->fields = [
            config('fields.email'), config('fields.password')
        ];

        $this->resetForm();
    }

    public function handleLogin(){

        $this->validate([
            'form.email' => 'required|email',
            'form.password' => 'required'
        ], [] , ['form.email' => 'email' , 'form.password' => 'password']);

        $email = str($this->form['email'])->lower();
        $password = md5($this->form['password']);


        $cms_user = CmsUser::where('email' , $email)->where('password' , $password)->whereNull('deleted_at')->first();


        if(!$cms_user){
            $this->sendError("Wrong Credentials" , "You have entered an invalid email and password");
            return;
        }

        session([
            'cms_user' => $cms_user
        ]);

        $this->redirect("/cms" , navigate: true);     
    }


    public function render()
    {
        return view('CMSView::pages.form.components.login-form');
    }

}
