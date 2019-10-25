<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Api\UserRequest;
use App\Server\TLSSigAPIv2;

class UsersController extends Controller
{

    public function enroll(Request $request)
    {

       if (!$request->username || !$request->password) {

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

       $user = User::where('username',$request->username)->count();

        if ($user) {

          return response()->json(['code'=>201,'message'=>'账号已存在']);

        }

        User::create([
            'username' => $request->username,
            'phone'    =>  $verifyData['phone'],
            'password' => bcrypt($request->password),
        ]);

        $userSig = $this->getUserSig($request->username);

        return response()->json(['code'=>200,'message'=>'注册成功','userSig'=>$this->$userSig]);
    }

    protected function getUserSig(String $username)
    {

        $tls = new TLSSigAPIv2(env('SDK_APPID'),env('SECRET_KEY'));

        $user = $tls->genSig($username);

        return $user;
    }

    /**
     *
     */

    public function login()
    {

    }
}
