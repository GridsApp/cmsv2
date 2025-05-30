<?php

namespace twa\cmsv2\Http\Controllers;

use App\Interfaces\MovieRepositoryInterface;
use App\Interfaces\ReportRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ReportsController extends Controller
{
    public function render()
    {

        $reports = get_report_classes();

        // dd($reports);

        foreach ($reports as $slug => $report) {

            $class = new $report;

            $result[] =  [
                'slug' => $slug,
                'label' => $class->label
            ];
        }


        return view("CMSView::pages.reports.list", ['result' => $result]);
    }

    public function show($slug)
    {

        $reports = get_report_classes();

        $class = $reports[$slug] ?? null;

        if (!$class) {
            abort(404);
        }

        $class = new $class;


      

        return view("CMSView::pages.reports.show", ['slug' => $slug,'pagination' => $class->pagination , 'title'=>$class->label]);
    }



    // public function getClasses()
    // {




    //     $files = [];

    //     $directories = config('reports.directories');

    //     foreach ($directories as $directory) {
    //         $files = [...$files, ...File::files(str(app_path($directory))->replaceFirst('/app', '')->toString())];
    //     }


    //     $result = [];
    //     foreach ($files as $file) {


    //         $path = str_replace(app_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());

    //         $full_path_of_class_including_namespace = '\\App\\' . str_replace(['/', '.php'], ['\\', ''], $path);

    //         $className = pathinfo($file, PATHINFO_FILENAME);

    //         $slug = Str::of($className)
    //             ->snake()
    //             ->replace('_', '-')->toString();
    //         // dd($slug);
    //         $result[$slug] = $full_path_of_class_including_namespace;
    //     }

    //     return $result;

    // }
}
