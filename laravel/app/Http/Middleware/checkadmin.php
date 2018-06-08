<?php

namespace App\Http\Middleware;

use Closure;

//https://laravel.com/docs/5.6/middleware
//https://laravel.com/docs/5.6/requests
// $request->is('admin/*')
/*
The path method returns the request's path information.
So, if the incoming request is targeted at http://domain.com/foo/bar,
the path method will return foo/bar:

$uri = $request->path();
*/
class checkadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('post')) {
            if($request->get('userlevel')){
              return response()->json([
                  'status' => 'ko',
                  'ret' => 'not admin'
              ], 201);
            }
        }

        return $next($request);
    }
}
