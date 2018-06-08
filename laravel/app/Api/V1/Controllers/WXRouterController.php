<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\User;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
// use App\PayNotify;
use App\Pclogin;
// use Illuminate\Contracts\Routing\ResponseFactory;
// use Illuminate\Http\Response;
use Carbon\Carbon;
use App\WeiXinPay;
use App\WinXinRefund;
use App\myUtil;
use Config;

class WXRouterController extends Controller
{
    private $APPID ;//填写您的appid。
    private $APPSECRET;

    public function __construct()
    {
        $this->APPID = Config::get('weixinpay.xcx.app_id');//填写您的appid。
        $this->APPSECRET = Config::get('weixinpay.xcx.app_secret');
        // $this->middleware('jwt.auth', []);
        $exceptapi=[
          'wxlogin',
          'getwxqrcode',
          'PC_checkwxqrcode',
        ];
        // $this->middleware('auth:api', ['except'=>$exceptapi]);
        $this->middleware('jwt.auth', ['except'=>$exceptapi]);
    }

    public function wxlogin(JWTAuth $JWTAuth,$code)
    {
      //get openid
      $guzzleclient = new Client();
      //https://developers.weixin.qq.com/miniprogram/dev/api/api-login.html#wxloginobject
      $res = $guzzleclient->request('GET', 'https://api.weixin.qq.com/sns/jscode2session',
        [
          'query' =>
            [
              'appid' => $this->APPID,//TODO: 做到配置项
              'secret' => $this->APPSECRET,
              'js_code' => 'JSCODE',
              'grant_type' => 'authorization_code',
              'js_code' => $code
            ]
        ]
        );

      $body = $res->getBody();
      $bodyjson = json_decode($body);
      // var_dump($bodyjson);
      // return;
      if($bodyjson && array_key_exists('openid',$bodyjson)){
        $openid = $bodyjson->openid;

        $user = User::where('name',$openid)->first();
        if($user){
          $newtoken = $JWTAuth->fromUser($user);
          $ret['token'] = $newtoken;
          $ret['expires_in'] = Auth::guard()->factory()->getTTL() * 600;
          $ret['userinfo'] = $user;

          return response()->json([
              'status' => 'ok',
              'ret'  => $ret
          ], 201);
        }else{//create new
          $myutil = new myUtil;
          $password_rand = $myutil->getRandString();
          $user = new User([
            'name' => $openid,
            'password' => $password_rand,
            'email' => $openid . '@abc.com',//TODO:修改邮箱后缀
          ]);
          if($user->save()){
            $newtoken = $JWTAuth->fromUser($user);
            $ret['token'] = $newtoken;
            $ret['expires_in'] = Auth::guard()->factory()->getTTL() * 600;
            $ret['userinfo'] = $user;

            return response()->json([
                'status' => 'ok',
                'ret'  => $ret,
                '$password_rand' => $password_rand
            ], 201);
          }else{
            return response()->json([
                'status' => 'ko',
                'ret'  => 'user create fail'
            ], 201);
          }
        }

      }else{
        return response()->json([
            'status' => 'ko',
            'ret'  => $bodyjson,
            // 'test' => $this->APPID
        ], 201);
      }


    }

    public function PC_checkwxqrcode(Request $request,JWTAuth $JWTAuth,$scene){
      $pclogin = Pclogin::where('scene',$scene)->first();
      if($pclogin){
        $createat = $pclogin->created_at;
        $createatformat = new Carbon($createat, 'Asia/Shanghai');
        $nowdate = Carbon::now('Asia/Shanghai');
        $diffdate = $nowdate->diffInMinutes($createatformat);

        if($diffdate>5){

          $pclogin->fill([
            'status' => '超时'
          ]);
          $pclogin->save();

          return response([
            "result"=>'ko',
            "ret"=>[
                'errorcode' => '超时',
                'error' => '超时,请重新生成授权二维码，尝试登录'
              ]
          ]);
        }

        if($pclogin->status == '已使用' || $pclogin->status == '超期'){
          return response([
            "result"=>'ko',
            "ret"=>[
                'errorcode' => $pclogin->status,
                'error' => '失效'
              ]
          ]);
        }
        if($pclogin->status == '拒绝' ){
          return response([
            "result"=>'ko',
            "ret"=>[
                'errorcode' => '拒绝',
                'error' => '已经拒绝'
              ]
          ]);
        }

        if($pclogin->status == '创建'){
          return response([
            "result"=>'ko',
            "ret"=>[
                'errorcode' => '创建',
                'error' => '请等待'
              ]
          ]);
        }

        if($pclogin->status == '使用中'){
          return response([
            "result"=>'ko',
            "ret"=>[
                'errorcode' => '使用中',
                'error' => '请等待'
              ]
          ]);
        }

        //允许的才可以登录

        $userid = $pclogin->userid;
        $User = new User;
        $isadmin = $User->isAdmin($userid);
        if($isadmin){

        }else{
          $pclogin->fill([
            'status' => '已使用'
          ]);
          $pclogin->save();
          return response()->json([
              'status' => 'ko',
              'ret' =>[
                  'errorcode' => '权限',
                  'error' => '不是管理者'
                ]

          ], 201);
        }

        $user = User::find($userid);

        $newtoken = $JWTAuth->fromUser($user);
        $ret['token'] = $newtoken;
        $ret['expires_in'] = Auth::guard()->factory()->getTTL() * 600;
        $ret['userinfo'] = $user;

        $pclogin->fill([
          'status' => '已使用'
        ]);
        $pclogin->save();

        return response([
          "result"=>'ok',
          "ret"=>$ret
        ]);


      }else{
        return response([
          "result"=>'ko',
          "ret"=>[
              'errorcode' => '黑客',
              'error' => '你是黑客'
            ]
        ]);
      }
    }
    public function rejectwxqrcode(Request $request,$scene){

      $current_user_id =  Auth::id();
      //检查时间戳是否超期5分钟
      //检查scene是否存在，检查scene状态是创建
      $pclogin = Pclogin::where('scene',$scene)->first();
      if($pclogin){
        $createat = $pclogin->created_at;
        $createatformat = new Carbon($createat, 'Asia/Shanghai');
        $nowdate = Carbon::now('Asia/Shanghai');
        $diffdate = $nowdate->diffInMinutes($createatformat);

        if($diffdate>5){//5分钟 //TODO: 做到配置项

          $pclogin->fill([
            'status' => '超时'
          ]);
          $pclogin->save();

          return response([
            "result"=>'ko',
            "ret"=>[
                'errorcode' => 100,
                'error' => '超时,请重新生成授权二维码，尝试登录'
              ]
          ]);
        }

        if($pclogin->status == '超时' || $pclogin->status == '已使用'){
          return response([
            "result"=>'ko',
            "ret"=>[
                'errorcode' => 200,
                'error' => '超时/已使用,请重新生成授权二维码，尝试登录'
              ]
          ]);
        }

        if($pclogin->status == '拒绝' ){
          return response([
            "result"=>'ko',
            "ret"=>[
                'errorcode' => 300,
                'error' => '已经拒绝'
              ]
          ]);
        }

        // if($pclogin->status == '使用中' ){
        //   return response([
        //     "result"=>'ko',
        //     "ret"=>[
        //         'errorcode' => 300,
        //         'error' => '使用中,请等待'
        //       ]
        //   ]);
        // }

        //改为使用中
        $pclogin->fill([
          'userid' => $current_user_id,
          'status' => '拒绝'
        ]);
        $pclogin->save();

        return response([
          "result"=>'ok',
          "ret"=>$pclogin
        ]);

        //$date->diffInMinutes('2014-03-30 00:00:00');
      }else{
        return response([
          "result"=>'ko',
          "ret"=>[
              'errorcode' => 400,
              'error' => '你是黑客'
            ]
        ]);
      }

    }


        public function permitwxqrcode(Request $request,$scene){

          $current_user_id =  Auth::id();
          //检查时间戳是否超期5分钟
          //检查scene是否存在，检查scene状态是创建
          $pclogin = Pclogin::where('scene',$scene)->first();
          if($pclogin){
            $createat = $pclogin->created_at;
            $createatformat = new Carbon($createat, 'Asia/Shanghai');
            $nowdate = Carbon::now('Asia/Shanghai');
            $diffdate = $nowdate->diffInMinutes($createatformat);

            if($diffdate>5){

              $pclogin->fill([
                'status' => '超时'
              ]);
              $pclogin->save();

              return response([
                "result"=>'ko',
                "ret"=>[
                    'errorcode' => 100,
                    'error' => '超时,请重新生成授权二维码，尝试登录'
                  ]
              ]);
            }

            if($pclogin->status == '超时' || $pclogin->status == '已使用' || $pclogin->status == '允许'){
              return response([
                "result"=>'ko',
                "ret"=>[
                    'errorcode' => 200,
                    'error' => '超时/已使用,请重新生成授权二维码，尝试登录'
                  ]
              ]);
            }

            if($pclogin->status == '拒绝' ){
              return response([
                "result"=>'ko',
                "ret"=>[
                    'errorcode' => 300,
                    'error' => '已经拒绝'
                  ]
              ]);
            }

            // if($pclogin->status == '使用中' ){
            //   return response([
            //     "result"=>'ko',
            //     "ret"=>[
            //         'errorcode' => 300,
            //         'error' => '使用中,请等待'
            //       ]
            //   ]);
            // }

            //old 状态为：使用中或创建

            $pclogin->fill([
              'userid' => $current_user_id,
              'status' => '允许'
            ]);
            $pclogin->save();

            return response([
              "result"=>'ok',
              "ret"=>$pclogin
            ]);

            //$date->diffInMinutes('2014-03-30 00:00:00');
          }else{
            return response([
              "result"=>'ko',
              "ret"=>[
                  'errorcode' => 400,
                  'error' => '你是黑客'
                ]
            ]);
          }

        }

    public function checkwxqrcode(Request $request,$scene){

      $current_user_id =  Auth::id();
      //检查时间戳是否超期5分钟
      //检查scene是否存在，检查scene状态是创建
      $pclogin = Pclogin::where('scene',$scene)->first();
      if($pclogin){
        $createat = $pclogin->created_at;
        $createatformat = new Carbon($createat, 'Asia/Shanghai');
        $nowdate = Carbon::now('Asia/Shanghai');
        $diffdate = $nowdate->diffInMinutes($createatformat);

        if($diffdate>5){//5分钟 //TODO: 做到配置项

          $pclogin->fill([
            'status' => '超时'
          ]);
          $pclogin->save();

          return response([
            "result"=>'ko',
            "ret"=>[
                'errorcode' => 100,
                'error' => '超时,请重新生成授权二维码，尝试登录'
              ]
          ]);
        }

        if($pclogin->status == '超时' || $pclogin->status == '已使用' || $pclogin->status == '允许'){
          return response([
            "result"=>'ko',
            "ret"=>[
                'errorcode' => 200,
                'error' => '超时/已使用,请重新生成授权二维码，尝试登录'
              ]
          ]);
        }

        if($pclogin->status == '拒绝' ){
          return response([
            "result"=>'ko',
            "ret"=>[
                'errorcode' => 300,
                'error' => '已经拒绝'
              ]
          ]);
        }

        if($pclogin->status == '使用中' ){
          return response([
            "result"=>'ko',
            "ret"=>[
                'errorcode' => 300,
                'error' => '使用中,请等待'
              ]
          ]);
        }

        //改为使用中
        $pclogin->fill([
          'userid' => $current_user_id,
          'status' => '使用中'
        ]);
        $pclogin->save();

        return response([
          "result"=>'ok',
          "ret"=>$pclogin
        ]);

        //$date->diffInMinutes('2014-03-30 00:00:00');
      }else{
        return response([
          "result"=>'ko',
          "ret"=>[
              'errorcode' => 400,
              'error' => '你是黑客'
            ]
        ]);
      }

    }

    /**
     * WX router
     *
     * @param WXRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getwxopenid(Request $request)
    {
      //$code=Input::input('code');
      $code = $request->query('code');
      // echo $code
      //echo $code;
      $guzzleclient = new Client();
      $res = $guzzleclient->request('GET', 'https://api.weixin.qq.com/sns/jscode2session',
        [
          'query' =>
            [
              'appid' => $this->APPID,//TODO: 做到配置项
              'secret' => $this->APPSECRET,
              'js_code' => 'JSCODE',
              'grant_type' => 'authorization_code',
              'js_code' => $code
            ]
        ]
        );

        $body = $res->getBody();
        //echo $body;

        return response($body);
            // ->json($body);
    }

    public function getwxqrcode(Request $request)
    {
      //$code=Input::input('code');
      // $scene = $request->query('scene');
      // echo $code
      //echo $code;
      $guzzleclient = new Client();
      $res = $guzzleclient->request('GET', 'https://api.weixin.qq.com/cgi-bin/token',
        [
          'query' =>
            [
              'appid' => $this->APPID,//TODO: 做到配置项
              'secret' => $this->APPSECRET,
              'grant_type' => 'client_credential'
            ]
        ]
        );

        $body = $res->getBody();

        // return response($body);
        //echo $body;
        $bodyjson = json_decode($body);
        $access_token = $bodyjson->access_token;

        //get qr
        $qrurl = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$access_token;
        // $response = $guzzleclient->post($qrurl, [
        //     'headers'=>['Content-Type'=>'application/json'],
        //     'json' => [
        //       'scene' => 'scene_pclogin_123456',
        //       'page' => 'pages/index/index'
        //     ]
        // ]);
        //
        // $qrbody = $response->getBody();
        //
        //
        // return response()->streamDownload($qrbody,'qrcode.jpeg');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $qrurl);
        curl_setopt($ch, CURLOPT_POST, 1);

        $sceneRand = rand(100000,999999);
        $sceneRandStr = (string)$sceneRand;
        $sceneTime = time();
        $sceneTimeStr = (string)$sceneTime;
        $scene = 'scene_pclogin_' . $sceneRandStr .$sceneTimeStr;

        $modelhandle= new Pclogin;
        $modelhandle->fill([
          'scene' => $scene,
          'status' => '创建'
        ]);
        $modelhandle->save();

        $data =  [
              'scene' => $scene,
              'page' => 'pages/index/index'
            ];
        $data_string =  json_encode($data);
        // var_dump($data_string);
        // return;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1; zh-CN) AppleWebKit/535.12 (KHTML, like Gecko) Chrome/22.0.1229.79 Safari/535.12");
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $output = curl_exec($ch);
        curl_close($ch);
        // var_dump($output);
        // return;
        $base64 =  chunk_split(base64_encode($output));
        // 输出'<img src="data:image/jpg/png/gif;base64,' . $base64 .'" >';
        $encode = "data:image/jpg/png/gif;base64," . $base64 ;
        // $encode = '<img src="data:image/jpg/png/gif;base64,' . $base64 .'" >';
        // echo $encode;
        // echo $base64;
        // print_r($output);
        // return response($qrbody);
            // ->json($body);
        return response([
          "result"=>'ok',
          "ret"=>$encode,
          "scene"=>$scene
        ]);
    }

    //wechat pay

    public function dowxrefund(Request $request){
      $wxrefund = new WinXinRefund('openid','outTradeNo',10,'outRefundNo',10);
      // $ret = '';
      $ret = $wxrefund->refund();


      if($ret==null || $ret==false){
        return response([
          "result"=>'ko',
          "ret"=>$ret
        ]);
      }

      if($ret['return_code'] == 'FAIL'){
        return response([
          "result"=>'ko',
          "ret"=>$ret
        ]);
      }

      return response([
        "result"=>'ok',
        "ret"=>$ret
      ]);
    }

    public function predopay(Request $request){
      $wxpay = new WeiXinPay('openid','outTradeNo',10);
      // $ret = '';
      $ret = $wxpay->pay();
      if($ret==null){
        return response([
          "result"=>'ko',
          "ret"=>$ret
        ]);
      }

      return response([
        "result"=>'ok',
        "ret"=>$ret
      ]);
    }
}
