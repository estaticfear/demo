<?php

namespace Cmat\Vnpay\Supports;

use Cmat\Vnpay\Models\VnpayTransaction;
use Eloquent;
use Request;
use Illuminate\Support\Facades\Http;

class Helper
{
    public static function sanboxURLs(): array
    {
        return config('plugins.vnpay.general.sanbox', []);
    }

    public static function prodURLs(): array
    {
        return config('plugins.vnpay.general.prod', []);
    }

    public static function generatePaymentURL(VnpayTransaction $transaction, $return_url)
    {
        $amount = $transaction->amount;
        $user_ip = $transaction->ip;
        $user_locale = 'vn';
        $txn_ref = $transaction->id;
        $bank_code = $transaction->bank_code;

        $terminal_id = setting('vnpay_terminal_id');

        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $terminal_id,
            "vnp_Amount" => $amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $startTime,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $user_ip,
            "vnp_Locale" => $user_locale,
            "vnp_OrderInfo" => "GD:" . $txn_ref,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $return_url,
            "vnp_TxnRef" => $txn_ref,
            "vnp_ExpireDate" => $expire
        );

        if (!empty($bank_code)) {
            $inputData['vnp_BankCode'] = $bank_code;
        }
        $urls = self::getURLs();

        $query = self::getSignatureUrlAndSecureHash($inputData)['query'];

        return $urls['url'] . '?' . $query;
    }

    public static function getURLs() {
        $enable_sanbox = setting('vnpay_enable_sanbox');
        return $enable_sanbox ? self::sanboxURLs() : self::prodURLs();
    }

    public static function getSignatureUrlAndSecureHash($data = []) {
        $vnpay_hash_secret = setting('vnpay_hash_secret');

        $input_data = $data;

        if (isset($input_data['vnp_SecureHash'])) {
            unset($input_data['vnp_SecureHash']);
        }

        ksort($input_data);

        $i = 0;
        $hashdata = "";
        $query = "";
        foreach ($input_data as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnpay_hash_secret);

        $query .= 'vnp_SecureHash=' . $vnpSecureHash;

        return [
            'query' => $query,
            'security_hash' => $vnpSecureHash,
        ];
    }

    public static function getVnpayTransactionInfo(VnpayTransaction $vnpayTransaction) {
        $terminal_id = setting('vnpay_terminal_id');

        $data = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'querydr',
            'vnp_TmnCode' => $terminal_id,
            'vnp_TxnRef' => $vnpayTransaction->id,
            'vnp_OrderInfo' => "GD:" . $vnpayTransaction->id,
            'vnp_TransDate' => date('YmdHis', strtotime($vnpayTransaction->created_at)),
            'vnp_CreateDate' => date("YmdHis"),
            'vnp_IpAddr' => env('CURRENT_SERVER_IP'),
        ];

        $urls = self::getURLs();
        $api_url = $urls['api_url'];
        $query = self::getSignatureUrlAndSecureHash($data)['query'];

        $response = Http::get($api_url . '?' . $query);
        $body = $response->body();

        parse_str($body, $response_data);

        return $response_data;
    }
}
