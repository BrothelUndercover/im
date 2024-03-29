<?php
return [
    // HTTP 请求的超时时间（秒）
    'timeout' => 60,

    // 默认发送配置
    'default' => [
        // 网关调用策略，默认：顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'juhe',
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'juhe' => [
            'app_key' => env('SMS_JUHE_APPKEY'),
        ],
    ],
];
