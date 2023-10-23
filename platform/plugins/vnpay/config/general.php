<?php

return [
    'sanbox' => [
        'url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
        'api_url' => 'http://sandbox.vnpayment.vn/merchant_webapi/merchant.html',
        'api_transactions' => 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction',
        'expire_time_str' => '+15 minutes',
    ],
    'prod' => [
        'url' => 'https://vnpayment.vn/paymentv2/vpcpay.html',
        'api_url' => 'http://vnpayment.vn/merchant_webapi/merchant.html',
        'api_transactions' => 'https://vnpayment.vn/merchant_webapi/api/transaction',
        'expire_time_str' => '+15 minutes',
    ]
];
