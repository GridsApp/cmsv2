<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use twa\cmsv2\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Lang;


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


if (!function_exists('___')) {
    function ___($key)
    {

        return Lang::has($key) ? __($key) : null;
        
    }
}

if (!function_exists('routeObject')) {
    function routeObject($name, $params = [])
    {

        return [
            'name' => $name,
            'params' => $params
        ];
    }
}

if (!function_exists('get_breadcrumbs_link')) {
    function get_breadcrumbs_link($array, $path = [])
    {
        $result = [];

        foreach ($array ?? [] as $key => $item) {

            if (isset($item['link'])) {
                $item['link'] = is_string($item['link']) ? $item['link'] : parse_url(route($item['link']['name'], $item['link']['params']), PHP_URL_PATH);
                $result[$item['link']] = array_merge($path, [$key]);
            }

            if (isset($item['children'])) {
                $result = array_merge($result, get_breadcrumbs_link($item['children'], array_merge($path, [$key])));
            }
        }

        return $result;
    }
}

if (!function_exists('get_breadcrumbs_items')) {
    function get_breadcrumbs_items($menu, $array, $routePath)
    {

        $result = [];
        $current = $menu;

        foreach ($array as $index) {
            if (isset($current[$index]) || isset($current["children"][$index])) {
                $current = $current[$index] ?? $current["children"][$index];

                if (isset($current['link'])) {
                }
                $result[] = $current;
            } else {
                $current = null;
                break;
            }
        }


        return $result;
    }
}

if (!function_exists('get_breadcrumbs')) {
    function get_breadcrumbs()
    {
        $explode = explode("/", request()->path());
        $routePath = "/" . $explode[0];
        $menu = config('menu');
        $result = get_breadcrumbs_link($menu);
        $indexes = $result[$routePath] ?? null;
        if (!$indexes) {
            return [];
        }

        $breadcrumbs = get_breadcrumbs_items($menu, $indexes, $routePath);

        unset($explode[0]);

        $explodes = collect(array_values($explode))->filter()->values()->toArray();
        foreach ($explodes as $explode) {
            // dd($explodes);
            $breadcrumbs[] = [
                "label" => ucfirst($explode),
                'link' => ""
            ];
        }
        return $breadcrumbs;
    }
}


if (!function_exists('get_class_namespace')) {
    function get_class_namespace($filePath)
    {
        $lines = file($filePath);

        foreach ($lines as $line) {
            if (preg_match('/^namespace\s+(.+?);$/', $line, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}

if (!function_exists('get_class_name')) {
    function get_class_name($filePath)
    {
        $lines = file($filePath);
        foreach ($lines as $line) {
            if (preg_match('/^class\s+(\w+)/', $line, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}
if (!function_exists('field_init')) {
    function field_init($field, $data = null)
    {
        return (new $field['type']($field))->initalValue($data);
    }
}

if (!function_exists('get_entity')) {
    function get_entity($slug)
    {

        if (!$slug) {
            return null;
        }

        $className = config('entity-mapping.' . $slug);

        return new $className;
    }
}

if (!function_exists('field_value')) {
    function field_value($field, $form, $debug = false)
    {

        return (new $field['type']($field))->value($form);

        if (!$field) {
            return null;
        }

        switch ($field['type'] ?? null) {


            case "toggle":
                return $form[$field['name']];

            case "theater-map":

                return json_encode($form[$field['name']]);

            case "file-upload":

                $files = [];
                $already_uploaded = [];


                if (isset($field['multiple']) && $field['multiple']) {
                    foreach ($form[$field['name']] ?? [] as $file) {

                        if (!$file) {
                            continue;
                        }

                        if ($file['progress'] == 102) {
                            $already_uploaded[] = $file['uploaded'];
                            continue;
                        }


                        $files[] = [
                            'file' => $file['uploaded'],
                            'crop' => isset($file['cropping']) ? [
                                'x' => $file['cropping']['x'],
                                'y' => $file['cropping']['y'],
                                'width' => $file['cropping']['width'],
                                'height' => $file['cropping']['height']
                            ] : null
                        ];
                    }


                    $upload_files = (new UploadController($field))->uploadFiles($files);

                    return json_encode([...$already_uploaded, ...$upload_files]);
                } else {

                    $file = $form[$field['name']];
                    if (!$file) {
                        return null;
                    }

                    if ($file['progress'] == 102) {
                        return $file['uploaded'];
                    }

                    return (new UploadController($field))->uploadFile([
                        'file' => $file['uploaded'],
                        'crop' => isset($file['cropping']) ? [
                            'x' => $file['cropping']['x'],
                            'y' => $file['cropping']['y'],
                            'width' => $file['cropping']['width'],
                            'height' => $file['cropping']['height']
                        ] : null
                    ]);
                }

                // return $form[$field['name']];

            case "select":
                if (isset($field['multiple']) && $field['multiple']) {

                    return is_array($form[$field['name']]) ? json_encode($form[$field['name']]) : null;
                } else {
                    return $form[$field['name']] ?? null;
                }

            default:
                return $form[$field['name']] ?? null;
        }
    }
}


if (!function_exists('get_report_classes')) {
    function get_report_classes()
    {
        $files = [];

       $mappings =  config('reports.mappings');

       if($mappings && is_array($mappings)){
            return $mappings;
       }


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

