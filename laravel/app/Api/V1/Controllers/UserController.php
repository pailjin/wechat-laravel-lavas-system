<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('jwt.auth', []);
        $exceptapi=[
          'wxlogintest'
        ];
        // $this->middleware('auth:api', ['except'=>$exceptapi]);
        $this->middleware('jwt.auth', ['except'=>$exceptapi]);

        $exceptapi_checkadmin=[
          // 'wxlogintest'
        ];
        $this->middleware('checkadmin', ['except'=>$exceptapi_checkadmin]);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::guard()->user());
    }

    public function updatebyuid(Request $request, $uid)
    {
        $cuser = User::find($uid);

        if(!$cuser){
          return response()->json([
              'status' => 'ko',
              'ret' => 'not find'
          ], 201);
        }

        $cuser->fill($request->all());

        if($cuser->save()){
          return response()->json([
              'status' => 'ok',
              'ret' => $cuser
          ], 201);
        }else{
          return response()->json([
              'status' => 'ko',
              'ret' => 'save fail'
          ], 201);
        }
    }

    public function wxlogintest(JWTAuth $JWTAuth,$code)
    {
      // $user = User::find(1);
      // $newtoken = $JWTAuth->fromUser($user);
      // $ret['token'] = $newtoken;
      // $ret['expires_in'] = Auth::guard()->factory()->getTTL() * 600;

      return response()->json([
          'status' => 'ok'
      ], 201);
    }



}
