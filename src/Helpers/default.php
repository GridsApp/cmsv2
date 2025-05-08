<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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


if (!function_exists('get_report_classes')) {
    function get_report_classes()
    {
        $files = [];

        $directories = config('reports.directories');
    
        foreach ($directories as $directory) {
            $files = [...$files, ...File::files(str(app_path($directory))->replaceFirst('/app', '')->toString())];
        }
    
    

        $result = [];
        foreach ($files as $file) {
    
          
    

            
            $path = str_replace(app_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());

            $full_path_of_class_including_namespace = '\\App\\' . str_replace(['/', '.php'], ['\\', ''], $path);
       
            $className = pathinfo($file, PATHINFO_FILENAME);
            
            $slug = Str::of($className)
                ->snake()
                ->replace('_', '-')->toString();

            $result[$slug] = $full_path_of_class_including_namespace;
        }
    
   
        return $result;
    }
}

