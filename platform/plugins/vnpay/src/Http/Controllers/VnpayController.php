<?php

namespace Cmat\Vnpay\Http\Controllers;

use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Setting\Supports\SettingStore;
use Cmat\Vnpay\Enums\VnpayTransactionStatusEnum;
use Cmat\Vnpay\Events\VnpayTransactionUpdatedEvent;
use Cmat\Vnpay\Http\Requests\VnpayRequest;
use Cmat\Vnpay\Repositories\Interfaces\VnpayInterface;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Vnpay\Supports\Helper;
use Illuminate\Http\Request;
use Exception;
use Cmat\Vnpay\Tables\VnpayTable;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Vnpay\Forms\VnpayForm;
use Cmat\Base\Forms\FormBuilder;

class VnpayController extends BaseController
{
    protected VnpayInterface $vnpayRepository;

    public function __construct(VnpayInterface $vnpayRepository)
    {
        $this->vnpayRepository = $vnpayRepository;
    }

    public function index(VnpayTable $table)
    {
        page_title()->setTitle(trans('plugins/vnpay::vnpay.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/vnpay::vnpay.create'));

        return $formBuilder->create(VnpayForm::class)->renderForm();
    }

    public function store(VnpayRequest $request, BaseHttpResponse $response)
    {
        $vnpay = $this->vnpayRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(VNPAY_MODULE_SCREEN_NAME, $request, $vnpay));

        return $response
            ->setPreviousUrl(route('vnpay.index'))
            ->setNextUrl(route('vnpay.edit', $vnpay->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $vnpay = $this->vnpayRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $vnpay));

        page_title()->setTitle(trans('plugins/vnpay::vnpay.edit') . ' "' . $vnpay->name . '"');

        return $formBuilder->create(VnpayForm::class, ['model' => $vnpay])->renderForm();
    }

    public function update(int|string $id, VnpayRequest $request, BaseHttpResponse $response)
    {
        $vnpay = $this->vnpayRepository->findOrFail($id);

        $vnpay->fill($request->input());

        $vnpay = $this->vnpayRepository->createOrUpdate($vnpay);

        event(new UpdatedContentEvent(VNPAY_MODULE_SCREEN_NAME, $request, $vnpay));

        return $response
            ->setPreviousUrl(route('vnpay.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function resync(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $tran = $this->vnpayRepository->findById($id);
            $vnpay_tran_detail = Helper::getVnpayTransactionInfo($tran);

            if (!isset($vnpay_tran_detail['vnp_ResponseCode']) || $vnpay_tran_detail['vnp_ResponseCode'] != '00') {
                return $response->setError()
                    ->setMessage(trans('plugins/vnpay::vnpay.notices.vnpay-call-error') . $vnpay_tran_detail['vnp_ResponseCode']);
            }

            $update_data = [];

            if (isset($vnpay_tran_detail['vnp_TransactionStatus'])) {
                $status = VnpayTransactionStatusEnum::FAIL;
                if ($vnpay_tran_detail['vnp_TransactionStatus'] == '00') {
                    $status = VnpayTransactionStatusEnum::SUCCESS;
                }
                if ($vnpay_tran_detail['vnp_TransactionStatus'] == '01') {
                    $status = VnpayTransactionStatusEnum::NOT_COMPLETED;
                }
                $update_data['status'] = $status;
            }

            if ($status == VnpayTransactionStatusEnum::SUCCESS) {
                $update_data['amount_ipn'] = $vnpay_tran_detail['vnp_Amount'];
            }

            if (isset($vnpay_tran_detail['vnp_Message'])) {
                $update_data['message'] = $vnpay_tran_detail['vnp_Message'];
            }
            if (isset($vnpay_tran_detail['vnp_TransactionNo'])) {
                $update_data['transaction_no'] = $vnpay_tran_detail['vnp_TransactionNo'];
            }
            if (isset($vnpay_tran_detail['vnp_TransactionStatus'])) {
                $update_data['transaction_status'] = $vnpay_tran_detail['vnp_TransactionStatus'];
            }
            if (isset($vnpay_tran_detail['vnp_TransactionType'])) {
                $update_data['transaction_type'] = $vnpay_tran_detail['vnp_TransactionType'];
            }
            if (isset($vnpay_tran_detail['vnp_BankCode'])) {
                $update_data['bank_code'] = $vnpay_tran_detail['vnp_BankCode'];
            }

            $tran->fill($update_data);
            $tran = $this->vnpayRepository->createOrUpdate($tran);

            $this->emitTransactionEvent($tran);

            event(new UpdatedContentEvent(VNPAY_MODULE_SCREEN_NAME, $request, $tran));
            return $response->setMessage(trans('plugins/vnpay::vnpay.notices.tran-info-updated'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    private function emitTransactionEvent(mixed $transaction)
    {
        event(new VnpayTransactionUpdatedEvent($transaction));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $vnpay = $this->vnpayRepository->findOrFail($id);

            $this->vnpayRepository->delete($vnpay);

            event(new DeletedContentEvent(VNPAY_MODULE_SCREEN_NAME, $request, $vnpay));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $vnpay = $this->vnpayRepository->findOrFail($id);
            $this->vnpayRepository->delete($vnpay);
            event(new DeletedContentEvent(VNPAY_MODULE_SCREEN_NAME, $request, $vnpay));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getVnpaySetting()
    {
        page_title()->setTitle(trans('plugins/vnpay::setting.title'));

        return view('plugins/vnpay::vnpay-setting');
    }

    public function postEditSettings(Request $request, BaseHttpResponse $response, SettingStore $settingStore)
    {
        $settingStore
            ->set('vnpay_is_enabled', $request->input('vnpay_is_enabled', false))
            ->set('vnpay_enable_sanbox', $request->input('vnpay_enable_sanbox', false))
            ->set('vnpay_terminal_id', $request->input('vnpay_terminal_id'))
            ->set('vnpay_hash_secret', $request->input('vnpay_hash_secret'))
            ->save();

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }
}
