<?php

namespace Cmat\Vnpay\Http\Controllers;

use BaseHelper;
use Cmat\Blog\Repositories\Interfaces\PostInterface;
use Cmat\Vnpay\Enums\VnpayTransactionStatusEnum;
use Cmat\Vnpay\Events\VnpayTransactionUpdatedEvent;
use Cmat\Vnpay\Repositories\Interfaces\VnpayInterface;
use Cmat\Vnpay\Supports\Helper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use SeoHelper;
use SlugHelper;
use Theme;

class PublicController extends Controller
{
    public function __construct(protected VnpayInterface $vnpayRepository)
    {
    }

    public function testPayment(Request $request)
    {
        $return_url = route('public.vnpay.verify-result');

        $tran_result = create_vnpay_transaction([
            'name' => 'name',
            'target_id' => 1,
            'target_type' => 'target_type',
            'amount' => 10000,
            'language' => 'language',
            'ip' => request()->ip(),
            'order_type' => 'order_type',
        ], $return_url);

        return redirect($tran_result['payment_url']);
    }

    public function verifyResult(Request $request) {
        $data = $request->query();
        $inputData = array();

        $vnp_ResponseCode = $request->query('vnp_ResponseCode');
        $vnp_SecureHash = $request->query('vnp_SecureHash');

        foreach ($data as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $secureHash = Helper::getSignatureUrlAndSecureHash($inputData)['security_hash'];

        $status = 'SUCCESS';
        if ($secureHash == $vnp_SecureHash) {
            if ($vnp_ResponseCode != '00') {
                $status = 'FAIL';
            }
        } else {
            $status = 'INVALID SIGNATURE';
        }

        $data = [
            'view' => 'vnpay-result',
            'default_view' => 'plugins/vnpay::themes.vnpay-result',
            'data' => [
                'status' => $status
            ]
        ];

        return Theme::scope($data['view'], $data['data'], $data['default_view'])->render();
    }

    public function ipn(Request $request) {
        $vnp_HashSecret = setting('vnpay_hash_secret');

        $inputData = array();
        $returnData = array();

        $queries = $request->query();
        foreach ($queries as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnpTranId = $inputData['vnp_TransactionNo']; //Mã giao dịch tại VNPAY
        $vnp_BankCode = $inputData['vnp_BankCode']; //Ngân hàng thanh toán
        $vnp_Amount = $inputData['vnp_Amount']/100; // Số tiền thanh toán VNPAY phản hồi

        $Status = VnpayTransactionStatusEnum::NEW; // Là trạng thái thanh toán của giao dịch chưa có IPN lưu tại hệ thống của merchant chiều khởi tạo URL thanh toán.
        $orderId = $inputData['vnp_TxnRef'];

        try {
            //Check Orderid
            //Kiểm tra checksum của dữ liệu
            if ($secureHash == $vnp_SecureHash) {
                //Lấy thông tin đơn hàng lưu trong Database và kiểm tra trạng thái của đơn hàng, mã đơn hàng là: $orderId
                //Việc kiểm tra trạng thái của đơn hàng giúp hệ thống không xử lý trùng lặp, xử lý nhiều lần một giao dịch
                //Giả sử: $order = mysqli_fetch_assoc($result);
                $order = $this->vnpayRepository->findById($orderId);

                if (!empty($order)) {
                    if($order->amount == $vnp_Amount) //Kiểm tra số tiền thanh toán của giao dịch: giả sử số tiền kiểm tra là đúng. //$order["Amount"] == $vnp_Amount
                    {
                        if ($order->status == VnpayTransactionStatusEnum::NEW) {
                            if ($inputData['vnp_ResponseCode'] == '00' && $inputData['vnp_TransactionStatus'] == '00') {
                                $Status = VnpayTransactionStatusEnum::SUCCESS; // Trạng thái thanh toán thành công
                            } else {
                                $Status = VnpayTransactionStatusEnum::FAIL; // Trạng thái thanh toán thất bại / lỗi
                            }
                            //Trả kết quả về cho VNPAY: Website/APP TMĐT ghi nhận yêu cầu thành công
                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'Confirm Success';

                            //Cài đặt Code cập nhật kết quả thanh toán, tình trạng đơn hàng vào DB
                            $transaction = $this->vnpayRepository->update([
                                'id' => $orderId
                            ], [
                                'status' => $Status,
                                'amount_ipn' => $vnp_Amount,
                                'ipn_call_at' => \Carbon\Carbon::now(),
                            ]);

                            $this->emitTransactionEvent($transaction);
                        } else {
                            $returnData['RspCode'] = '02';
                            $returnData['Message'] = 'Order already confirmed';
                        }
                    }
                    else {
                        $returnData['RspCode'] = '04';
                        $returnData['Message'] = 'invalid amount';
                    }
                } else {
                    $returnData['RspCode'] = '01';
                    $returnData['Message'] = 'Order not found';
                }
            } else {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Invalid signature';
            }
        } catch (Exception $e) {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknown error';
        }

        return $returnData;
    }

    private function emitTransactionEvent(mixed $transaction)
    {
        event(new VnpayTransactionUpdatedEvent($transaction));
    }
}
