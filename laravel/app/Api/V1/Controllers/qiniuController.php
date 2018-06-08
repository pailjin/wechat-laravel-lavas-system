<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Qiniu\Auth;
use Config;

class qiniuController extends Controller
{
    private $COMMON_AK;
    private $COMMON_SK;
    private $COMMON_BUKET_NAME
    public function __construct()
    {
        $this->COMMON_AK = Config::get('qiniu.commonbuket.AK');
        $this->COMMON_SK = Config::get('qiniu.commonbuket.SK');
        $this->COMMON_BUKET_NAME = Config::get('qiniu.commonbuket.Name');
        // $this->middleware('jwt.auth', []);
        $exceptapi=[
          // 'uptoken'
        ];
        // $this->middleware('auth:api', ['except'=>$exceptapi]);
        $this->middleware('jwt.auth', ['except'=>$exceptapi]);
    }
    /**
     * qiniu router
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uptoken(Request $request)
    {
        $accessKey=$this->COMMON_AK;
        $secretKey=$this->COMMON_SK;
        $bucketName=$this->COMMON_BUKET_NAME;
        
        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucketName);

        return response()
            ->json([
              'uptoken' =>$token
            ]
          );
    }
}
