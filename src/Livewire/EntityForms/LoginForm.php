<?php

namespace twa\cmsv2\Livewire\EntityForms;


use twa\cmsv2\Models\CmsUser;
use twa\cmsv2\Traits\FormTrait;
use twa\uikit\Traits\ToastTrait;
use Livewire\Component;
use twa\cmsv2\Models\CmsUserRolePermission;
use twa\cmsv2\Traits\PermissionsTrait;

class LoginForm extends Component
{
    use FormTrait, ToastTrait,PermissionsTrait;


    public function mount()
    {

        $this->fields = [
            config('fields.email'),
            config('fields.password')
        ];

        $this->resetForm();
    }


    // public function getPermissions($cms_user)
    // {
    //     if (empty($cms_user['roles'])) {
    //         return [];
    //     }

    //     $role_ids = collect($cms_user['roles'])->flatten()->toArray();


    //     if (empty($role_ids)) {
    //         return [];
    //     }

    //     $role_permissions = CmsUserRolePermission::whereNull('cms_user_role_permission.deleted_at')
    //         ->whereIn('cms_user_role_id', $role_ids)
    //         ->join('cms_permissions', 'cms_permissions.id', '=', 'cms_user_role_permission.cms_permission_id')
    //         ->select('cms_permissions.key as permission_key')
    //         ->get();

    //     if ($role_permissions->isEmpty()) {
    //         return [];
    //     }
    //     $permissions = $role_permissions->pluck('permission_key')->toArray();
       
    //     return $permissions;
    // }


    public function handleLogin()
    {

        
        $this->validate([
            'form.email' => 'required|email',
            'form.password' => 'required'
        ], [], ['form.email' => 'email', 'form.password' => 'password']);

        $email = str($this->form['email'])->lower();
        $password = md5($this->form['password']);


        // dd($password);
        $cms_user = CmsUser::where('email', $email)->where('password', $password)->whereNull('deleted_at')->first();


        if (!$cms_user) {
            $this->sendError("Wrong Credentials", "You have entered an invalid email and password");
            return;
        }

        $permissions = $this->getPermissions($cms_user);

        
        session([
            'cms_user' => $cms_user,
            'field_permissions' => $cms_user->attributes,
            'cms_user_permissions' => $permissions
        ]);

        $this->redirect("/cms", navigate: true);
    }


    public function render()
    {
        return view('CMSView::pages.form.components.login-form');
    }
}
