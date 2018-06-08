<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function(Router $api) {
        // $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        // $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        // $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        // $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');

        $api->post('logout', 'App\\Api\\V1\\Controllers\\LogoutController@logout');
        // $api->post('refresh', 'App\\Api\\V1\\Controllers\\RefreshController@refresh');
        // $api->get('me', 'App\\Api\\V1\\Controllers\\UserController@me');


    });
    //user info
    $api->get('wxlogin/{code}', 'App\\Api\\V1\\Controllers\\WXRouterController@wxlogin');
    $api->post('user/updatebyuid/{uid}', 'App\\Api\\V1\\Controllers\\UserController@updatebyuid');
    $api->get('me', 'App\\Api\\V1\\Controllers\\UserController@me');


    //qiniu
    $api->get('qiniu/uptoken', 'App\\Api\\V1\\Controllers\\qiniuController@uptoken');

    //PC login
    $api->get('getwxqrcode', 'App\\Api\\V1\\Controllers\\WXRouterController@getwxqrcode');
    $api->get('checkwxqrcode/{scene}', 'App\\Api\\V1\\Controllers\\WXRouterController@checkwxqrcode');
    $api->get('permitwxqrcode/{scene}', 'App\\Api\\V1\\Controllers\\WXRouterController@permitwxqrcode');
    $api->get('rejectwxqrcode/{scene}', 'App\\Api\\V1\\Controllers\\WXRouterController@rejectwxqrcode');
    $api->get('pccheckwxqrcode/{scene}', 'App\\Api\\V1\\Controllers\\WXRouterController@PC_checkwxqrcode');

    //weixin pay
    $api->get('wxpay/getprepay', 'App\\Api\\V1\\Controllers\\WXRouterController@predopay');
    $api->get('wxpay/dorefund', 'App\\Api\\V1\\Controllers\\WXRouterController@dowxrefund');


    $api->get('wxlogintest/{code}', 'App\\Api\\V1\\Controllers\\UserController@wxlogintest');

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        $api->get('protected', function() {
            return response()->json([
                'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.'
            ]);
        });

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
    });

    $api->get('hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });
});
