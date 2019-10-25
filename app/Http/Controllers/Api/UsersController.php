<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Api\UserRequest;
use App\Server\TLSSigAPIv2;
use GuzzleHttp\Client;

class UsersController extends Controller
{

    //注册
    public function enroll(Request $request)
    {

       if (!$request->mobile || !$request->password) {

           return response()->json(['code'=>201,'message'=>'账号或者密码必填']);
       }

       if (!$request->verify_code) {

           return response()->json(['code'=>201,'message'=>'手机验证码必填']);
       }

        $verifyData = \Cache::get($request->mobile);

        if (!$verifyData) {

            return response()->json(['code'=>402,'message'=>'验证码已失效']);
        }

        if (!hash_equals($verifyData['code'],$request->verify_code)) {

            return response()->json(['code'=>402,'message'=>'验证码错误']);
        }

       $user = User::where('mobile',$request->mobile)->count();

        if ($user) {

          return response()->json(['code'=>201,'message'=>'账号已存在']);

        }
        $result = $this->importIm($verifyData['mobile']);

        if ($result->getStatusCode() != 200) {

            return response()->json(['code'=>500,'message'=>'error']);
        };

        $user = User::create([
            'mobile'    =>  $verifyData['mobile'],
            'usersig'   => $this->getUserSig($verifyData['mobile']),
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['code'=>200,'message'=>'注册成功','userSig'=>$user->usersig]);
    }

    //获取userSig
    protected function getUserSig($account)
    {

        $tls = new TLSSigAPIv2(env('SDK_APPID'),env('SECRET_KEY'));

        $user = $tls->genSig($account);

        return $user;
    }

    //获取管理员信息
    protected function getAdminInfo()
    {
        $value = \Cache::rememberForever('admin_user',function(){

            return  User::where('nickname','administrator')->first();
        });
    }

    //导入账号
    protected function importIm($user_account)
    {
        //$admin = \Cache::get('admin_user');
        $random = str_pad(random_int(0,4294967295),32,0,STR_PAD_LEFT);
        $client = new Client();
        $url = "https://console.tim.qq.com/v4/im_open_login_svc/account_import?sdkappid=".env('SDK_APPID')."&identifier=administrator&usersig=eJwtzMsOgjAUBNB-6VZDbtHaQOJKY2pkIVEJW6QFb*RRSsVX-HcJsJwzk-mSc3ByOmWIT1wHyHzIKFVlMcOBE1liha01ia3NNGjlPdEaJfHpEsDlCwqrsVEvjUb1zhhzAWBUi*VgnHqeB5xNL5j3-yGPwqtpBKfPWOxild62Wfgomk83M*IdFMdNHtWHCPbdZU1*f2VkNYc_&random=".$random."&contenttype=json";

        $response = $client->request('POST', $url, [
            'Identifier'=> $user_account,
        ]);

        return $response;
    }

    //登录
    public function login(Request $request)
    {
        $user = User::where('mobile',$request->mobile)->first();

        if (!$user) {

           return response()->json(['code'=>404,'message'=>'您还没有账号,请先注册']);
        }

        return response()->json(['code'=>200,'message'=>'success','data'=>['identifier'=>$user->mobile,'usersig'=> $user->usersig]]);
    }
}
