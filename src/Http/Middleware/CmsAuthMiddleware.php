<?php

namespace twa\cmsv2\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CmsAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $cms_user = session('cms_user');
        if(!$cms_user){
            return redirect()->route('login');
        }

        

        request()->merge([
            'cms_user' => $cms_user
        ]);
    
        return $next($request);
    }
}
