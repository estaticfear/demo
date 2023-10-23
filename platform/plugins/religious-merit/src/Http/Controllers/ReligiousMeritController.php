<?php

namespace Cmat\ReligiousMerit\Http\Controllers;

use Cmat\ACL\Models\User;
use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Member\Repositories\Interfaces\MemberInterface;
use Cmat\ReligiousMerit\Enums\PaymentGateTypeEnum;
use Cmat\ReligiousMerit\Enums\ReligiousMeritStatusEnum;
use Cmat\ReligiousMerit\Events\UpdatedMeritFromOrToSuccessEvent;
use Cmat\ReligiousMerit\Http\Requests\ReligiousMeritRequest;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritInterface;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProductInterface;
use Cmat\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Cmat\ReligiousMerit\Tables\ReligiousMeritTable;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\ReligiousMerit\Forms\ReligiousMeritForm;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Ecommerce\Enums\ProductTypeEnum;
use Cmat\ReligiousMerit\Enums\ReligiousTypeEnum;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;
use Cmat\Setting\Supports\SettingStore;
use Illuminate\Support\Facades\Auth;

class ReligiousMeritController extends BaseController
{
    protected ReligiousMeritInterface $religiousMeritRepository;
    protected ReligiousMeritProductInterface $religiousMeritProductRepository;
    protected ReligiousMeritProjectInterface $religiousMeritProjectRepository;

    public function __construct(
        ReligiousMeritInterface $religiousMeritRepository,
        ReligiousMeritProductInterface $religiousMeritProductRepository,
        ReligiousMeritProjectInterface $religiousMeritProjectInterface
    )
    {
        $this->religiousMeritRepository = $religiousMeritRepository;
        $this->religiousMeritProductRepository = $religiousMeritProductRepository;
        $this->religiousMeritProjectRepository = $religiousMeritProjectInterface;
    }

    public function searchMembers(Request $request, MemberInterface $memberRepository)
    {
        $query = $request->query('q');
        $result = $memberRepository->getModel()
            ->where('email', 'like', '%' . $query . '%')
            ->select([
                'id',
                'email',
            ])
            ->limit(100)
            ->get()
            ->toArray();

        $result = array_map(function ($item) {
            return [
                'name' => $item['email'],
                'id' => $item['id'],
            ];
        }, $result);
        $result = array_merge([
            [
                'id' => '',
                'name' => 'N/A'
            ]
        ], $result);

        return [
            'message' => '',
            'data' => $result,
            'error' => false,
        ];
    }

    public function searchProjects(Request $request, ReligiousMeritProjectInterface $projectRepository)
    {
        $query = $request->query('q');

        $result = $projectRepository->getModel()
            ->where('name', 'like', '%' . $query . '%')
            ->select([
                'id',
                'name',
            ])
            ->limit(100)
            ->get()
            ->toArray();

        $result = array_map(function ($item) {
            return [
                'name' => $item['name'],
                'id' => $item['id'],
            ];
        }, $result);
        $result = array_merge([
            [
                'id' => '',
                'name' => 'N/A'
            ]
        ], $result);

        return [
            'message' => '',
            'data' => $result,
            'error' => false,
        ];
    }

    public function index(ReligiousMeritTable $table)
    {
        page_title()->setTitle(trans('plugins/religious-merit::religious-merit.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/religious-merit::religious-merit.create'));

        return $formBuilder->create(ReligiousMeritForm::class)->renderForm();
    }

    /**
     * call when create
     * @param ReligiousMeritRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(ReligiousMeritRequest $request, BaseHttpResponse $response)
    {
        dd($request->input());
        $religiousMerit = $this->religiousMeritRepository->createOrUpdate(
            array_merge($request->input(), [
                'author_id' => Auth::id(),
                'author_type' => User::class,
            ])
        );

        event(new CreatedContentEvent(RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $request, $religiousMerit));
        if ($religiousMerit->status == ReligiousMeritStatusEnum::SUCCESS
        || in_array($religiousMerit->type, [ReligiousTypeEnum::ARTIFACT, ReligiousTypeEnum::EFFORT]) && $religiousMerit->status == ReligiousMeritStatusEnum::IS_BOOKED) {
            event(new UpdatedMeritFromOrToSuccessEvent($religiousMerit->project_id));
        }

        return $response
            ->setPreviousUrl(route('religious-merit.index'))
            ->setNextUrl(route('religious-merit.edit', $religiousMerit->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $religiousMerit = $this->religiousMeritRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $religiousMerit));

        page_title()->setTitle(trans('plugins/religious-merit::religious-merit.edit') . ' "' . $religiousMerit->name . '"');

        return $formBuilder->create(ReligiousMeritForm::class, ['model' => $religiousMerit])->renderForm();
    }

    /**
     * Call when update
     * @param int|string $id
     * @param ReligiousMeritRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update(int|string $id, ReligiousMeritRequest $request, BaseHttpResponse $response)
    {
        $religiousMerit = $this->religiousMeritRepository->findOrFail($id);
        $old_status = $religiousMerit->status;

        $religiousMerit->fill($request->input());

        $religiousMerit = $this->religiousMeritRepository->createOrUpdate($religiousMerit);
        $new_status = $religiousMerit->status;

        event(new UpdatedContentEvent(RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $request, $religiousMerit));

        $project_id = $religiousMerit->project_id;
        if (in_array(ReligiousMeritStatusEnum::SUCCESS()->getValue(), [$old_status, $new_status])
        || in_array($religiousMerit->type, [ReligiousTypeEnum::ARTIFACT, ReligiousTypeEnum::EFFORT]) && in_array(ReligiousMeritStatusEnum::IS_BOOKED, [$old_status, $new_status])
            ) {
            event(new UpdatedMeritFromOrToSuccessEvent($project_id));
        }

        return $response
            ->setPreviousUrl(route('religious-merit.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $religiousMerit = $this->religiousMeritRepository->findOrFail($id);

            if ($religiousMerit->payment_gate != PaymentGateTypeEnum::TRANSFER) {
                return $response
                    ->setError()
                    ->setMessage(trans('plugins/religious-merit::notices.cant_not_delete_payment_gate_order_message'));
            }

            $this->religiousMeritRepository->delete($religiousMerit);

            event(new DeletedContentEvent(RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $request, $religiousMerit));
            if ($religiousMerit->status == ReligiousMeritStatusEnum::SUCCESS
                || in_array($religiousMerit->type, [ReligiousTypeEnum::ARTIFACT, ReligiousTypeEnum::EFFORT]) && $religiousMerit->status == ReligiousMeritStatusEnum::IS_BOOKED) {
                event(new UpdatedMeritFromOrToSuccessEvent($religiousMerit->project_id));
            }

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
            $religiousMerit = $this->religiousMeritRepository->findOrFail($id);
            $this->religiousMeritRepository->delete($religiousMerit);
            event(new DeletedContentEvent(RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $request, $religiousMerit));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getSettings()
    {
        page_title()->setTitle(trans('plugins/religious-merit::settings.title'));

        return view('plugins/religious-merit::bank-setting');
    }

    public function postEditSettings(Request $request, BaseHttpResponse $response, SettingStore $settingStore)
    {
        // if ($request->input('merit_slug') != setting('merit_slug')) {
        //     Slug::where('reference_type', ReligiousMeritProject::class)
        //     ->update(['prefix' => $request->input('merit_slug')]);
        // };
        $settingStore
            ->set('bank_transfer_bank_name', $request->input('bank_transfer_bank_name', false))
            ->set('bank_transfer_bank_name_text', $request->input('bank_transfer_bank_name_text', false))
            ->set('bank_transfer_bank_account_number', $request->input('bank_transfer_bank_account_number', false))
            ->set('bank_transfer_bank_account_name', $request->input('bank_transfer_bank_account_name'))
            // ->set('merit_slug', $request->input('merit_slug'))
            ->save();

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function getDetail(int|string $id, BaseHttpResponse $response)
    {
        $merit = $this->religiousMeritRepository->findOrFail($id, ['project', 'meritProducts']);

        return $response
        ->setData($merit)
        ->setMessage(__('Success'));
    }

    public function getMeritProducts(Request $request, BaseHttpResponse $response) {
        $merit_id = $request->merit_id;
        $product_type = $request->product_type;
        $merit = $this->religiousMeritRepository->findById($merit_id);
        if (!$merit) {
            return $response
            ->setError()
            ->setData([])
            ->setMessage(__('Không tìm thấy hoạt động được yêu cầu'));
        };
        $products = $merit->meritProducts($product_type)->get()->toArray();
        return $response
        ->setData($products)
        ->setMessage(__('Success'));
    }

    public function updateMeritProducts(Request $request, BaseHttpResponse $response) {
        $merit_id = $request->merit_id;
        $product_type = $request->product_type;
        $merit = $this->religiousMeritRepository->findById($merit_id);
        if (!$merit) {
            return $response
            ->setError()
            ->setData([])
            ->setMessage(__('Không tìm thấy hoạt động được yêu cầu'));
        };
        $project = $merit->project;

        $religious_merit_products = [];
        if ($product_type == ProductTypeEnum::PHYSICAL && !$project->can_contribute_artifact) {
            return $response
                ->setError()
                ->setMessage(__("Đóng góp bằng hiện vật hiện không được hỗ trợ"));
        }
        if ($product_type == ProductTypeEnum::DIGITAL && !$project->can_contribute_effort) {
            return $response
                ->setError()
                ->setMessage(__("Đóng góp bằng công sức hiện không được hỗ trợ"));
        }
        $products = $request->input('merit_products');
        if ($products == "null") {
            return $response
                ->setError()
                ->setMessage(__("Danh sách đóng góp không được bỏ trống"));
        }
        $project_product_ids = [];
        foreach ($products as $p) {
            array_push($project_product_ids, $p['merit_project_product_id']);
        }
        $projectProducts = $project->products($product_type)->whereIn('id', $project_product_ids)->get();
        $totalPrice = 0;
        foreach ($products as $p) {
            $projectProductId = $p['merit_project_product_id'];
            $qty = $p['quantity'];
            if ($qty > 0) {
                $projectProduct = $projectProducts->first(function ($p) use ($projectProductId) {
                    return $p->id == $projectProductId;
                });
                if ($projectProduct) {
                    // Kiểm tra số lượng
                    $totalPrice += $projectProduct->product->price * $qty;
                    array_push($religious_merit_products, [
                        'merit_id' => $merit->id,
                        'merit_project_product_id' => $projectProductId,
                        'product_name' => $projectProduct->product->name,
                        'qty' => $qty,
                        'price' => $projectProduct->product->price,
                        'product_type' => $projectProduct->product_type,
                    ]);
                }
            }
        }
        if (count($religious_merit_products)) {
            foreach ($religious_merit_products as $p) {
                $this->religiousMeritProductRepository->createOrUpdate($p, [
                    'merit_id' => $p['merit_id'],
                    'merit_project_product_id' => $p['merit_project_product_id']
                ]);
            }
            $this->religiousMeritRepository->createOrUpdate([
                'amount' => $totalPrice,
                // 'status' => ReligiousMeritStatusEnum::IS_BOOKED,
            ], [
                'id' => $merit->id
            ]);
            $meritProducts = $merit->meritProducts($product_type)->get();
            $merit->updateTotalMeritQty($meritProducts, false, true);
            // update project total amount
            $this->religiousMeritProjectRepository->updateProgress($merit->project_id);
        }
        return $response->setData(null)->setMessage(__('Success'));
    }
}
