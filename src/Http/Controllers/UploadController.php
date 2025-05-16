<?php

namespace twa\cmsv2\Http\Controllers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    public $field;

    public function __construct($field = null)
    {
        $this->field = $field;
    }

    public function uploadFiles($files)
    {

        $result = [];
        foreach ($files as $file) {
            $result[] =  $this->uploadFile($file);
        }

        return $result;
    }


    public function uploadFromSource($file , $content, $extension){


        $folder = uniqid();
        
        $new_file = "/data/" . $folder . '/original.' . $extension[1];
        Storage::disk('public')->put($new_file,$content);

        $file_content = Storage::disk('public')->get($new_file);

        // $thumbPath = "./storage/data/" . $folder . '/thumb.webp';
        // $imagePath = "./storage/data/" . $folder . '/image.webp';

        $storagePath = storage_path("app/public/data/{$folder}");

        $thumbPath = "{$storagePath}/thumb.webp";
        $imagePath = "{$storagePath}/image.webp";
        $manager = new ImageManager(new Driver);
        $image = $manager->read($file_content);


        if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
            return $folder . '.' . $extension;
        }


        if (isset($file['crop']) && $file['crop']) {
            $image->crop($file['crop']['width'], $file['crop']['height'], $file['crop']['x'], $file['crop']['y']);
        }

        $image->toWebp()->save($imagePath);

        if ($image->width() > 500) {
            $image->scale(width: 500);
        }

        $image->toWebp($this->field['quality'] ?? 75)->save($thumbPath);


        return $folder . '.' . $extension;
    }

    public function uploadFile($file)
    {

        $url = '/livewire-tmp/' . $file["file"];
        [$name , $extension] = explode(".", $file["file"]);

        $content = Storage::disk(config('livewire.temporary_file_upload.disk'))->get($url);

        return $this->uploadFromSource($file , $content , $extension);
    }
}
