<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;
use App\Server\TLSSigAPIv2;
use App\Models\User;

class VerificationCodesController extends Controller
{
    public function store(Request $request,EasySms $easySms)
    {

        $mobile = $request->mobile;

        $user = User::where('mobile',$request->mobile)->count();

        if ($user) {

           return response()->josn(['message'=>'手机号已注册'])->setStatusCode('302');
        }

        $code = str_pad(random_int(1,9999),4,0,STR_PAD_LEFT);
        try {
            $result = $easySms->send($mobile, [
                'content'  => '您的验证码为:'.$code,
                'template' => env('SMS_JUHE_TPLID'),
                'data' => [
                    'code' => $code
                ],
            ]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $e) {
            $message = $e->getException('juhe')->getMessage();
            return response()->json([
                'message'=> $message ?: '短信发送异常',
            ])->setStatusCode(500);
        }

        $key = $mobile;
        $expiredAt = now()->addMinutes(2);
        // 缓存验证码 10分钟过期。
        \Cache::put($key, ['mobile' => $mobile, 'code' => $code], $expiredAt);

        return response()->json([
            'message' => 'success',
        ])->setStatusCode(200);
    }
}
