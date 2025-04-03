<?php

if (!function_exists('cms_check_permission')) {
    function cms_check_permission($key)
    {

        $user = session('cms_user');


        if ($user && $user->super_admin == 1) {
            return true;
        }
        $permissions = request()->permissions ?? [];


        return in_array($key, $permissions);
    }
}
