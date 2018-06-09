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
use Auth;
use App\User;

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
// 用户等级：0 无效，10 普通user， 20 管理员 30 员工
        if ($request->isMethod('post')) {
            if($request->get('userlevel')){
              $user_id = Auth::id();
              if($user_id){
                $user = User::find($user_id);
                if($user){
                  if($user->userlevel != 20){
                    return response()->json([
                        'status' => 'ko',
                        'ret' => 'not admin'
                    ], 201);
                  }

                }

              }

            }
        }

        return $next($request);
    }
}
