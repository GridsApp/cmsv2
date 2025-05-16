<?php

use Illuminate\Support\Facades\Artisan;
use \Illuminate\Support\Facades\File;

Artisan::command('twa:migrate', function () {

    $this->comment("Started");

    (new twa\cmsv2\Http\Controllers\EntityController)->migrate();

    $this->comment("Finished");
})->purpose('Migrating tables');

Artisan::command('twa:entities', function () {
    $this->comment("Started");

    $files = File::files(app_path('Entities'));
    
    $other_files = File::files(base_path('vendor/twa/cmsv2/src/Entities'));


    $files = [...$files , ...$other_files];
    // $files = \Illuminate\Support\Facades\File::files();

    $config = [];

    foreach ($files as $file) {

        $filePath = $file->getRealPath();
        $namespace = get_class_namespace($filePath);
        $class = get_class_name($filePath);

        if ($namespace && $class) {
            $fullClassName = $namespace . '\\' . $class;
            if (class_exists($fullClassName)) {
                $checkClass = new $fullClassName;

                if ($checkClass->slug) {
                    $config[$checkClass->slug] = $fullClassName;
                }
            }
        }
    }
    $configContent = "<?php\n\nreturn " . var_export($config, true) . ";\n";

    file_put_contents(__DIR__ . '/../config/entity-mapping.php', $configContent);

    $this->comment("Finished");
})
    ->purpose('Map Entities');
