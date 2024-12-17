<?php

namespace twa\cmsv2\Http\Middleware;

// use App\Traits\APITrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use twa\cmsv2\Traits\APITrait;

class LanguageMiddleware
{

    use APITrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $lang = str(request()->header('Language' , 'en'))->lower()->toString();

        if(!collect(config('languages'))->where('prefix' , $lang)->first()){
            return $this->response(notification()->error("Language not found" , "Language not found"));
        }


        app()->setLocale($lang);

        return $next($request);
    }
}
