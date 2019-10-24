<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(Request $request,EasySms $easySms)
    {
        $phone = $request->mobile;

        $code = str_pad(random_int(1,9999),4,0,STR_PAD_LEFT);
        try {
            $result = $easySms->send($phone, [
                'content'  => '您的验证码为:'.$code,
                'template' => env('SMS_JUHE_TPLID'),
                'data' => [
                    'code' => $code
                ],
            ]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $e) {
            $message = $e->getException('juhe')->getMessage();
            abort(500, $message ?: '短信发送异常');
        }

        $key = $phone;
        $expiredAt = now()->addMinutes(2);
        // 缓存验证码 1分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

    }
}
