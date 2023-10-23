<?php

namespace Cmat\ReligiousMerit\Http\Controllers;

use App\Exports\ProjectReportExport;
use BaseHelper;
use Carbon\Carbon;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Ecommerce\Enums\ProductTypeEnum;
use Cmat\Media\Repositories\Interfaces\MediaFileInterface;
use Cmat\ReligiousMerit\Enums\PaymentGateTypeEnum;
use Cmat\ReligiousMerit\Enums\ReligiousMeritStatusEnum;
use Cmat\ReligiousMerit\Enums\ReligiousTypeEnum;
use Cmat\ReligiousMerit\Http\Requests\PublicReligiousMeritRequest;
use Cmat\ReligiousMerit\Models\ReligiousMerit;
use Cmat\ReligiousMerit\Models\ReligiousMeritProduct;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritInterface;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Theme;
use Cmat\ReligiousMerit\Services\ReligiousMeritProjectService;
use Excel;
use Illuminate\Support\Facades\Session;
use SlugHelper;
use Exception;
use RvMedia;
use Illuminate\Support\Arr;

class PublicController extends Controller
{
    public function __construct(
        protected MediaFileInterface $fileRepository
    ) {
    }

    public function getAvailableProjects(Request $request, ReligiousMeritProjectInterface $religiousMeritProjectRepository)
    {
        Theme::asset()
            ->usePath(false)
            ->add('rm-css', asset('vendor/core/plugins/religious-merit/css/rm-public.css'), [], [], '1.0.0');
        Theme::asset()
            ->container('footer')
            ->usePath(false)
            ->add(
                'modal-merit-js',
                asset('vendor/core/plugins/religious-merit/js/modal-merit.js'),
                ['jquery'],
                [],
                '1.0.0'
            );

        $query = BaseHelper::stringify($request->input('q'));

        $projects = $religiousMeritProjectRepository->getAvailableProjects($query);

        $data = [
            'view' => 'religious-merit-available-projects',
            'default_view' => 'plugins/religious-merit::themes.available-projects',
            'data' => [
                'projects' => $projects
            ]
        ];

        return Theme::layout('no-sidebar')->scope($data['view'], $data['data'], $data['default_view'])->render();
    }

    public function getFinishedProjects(Request $request, ReligiousMeritProjectInterface $religiousMeritProjectRepository)
    {
        Theme::asset()
            ->usePath(false)
            ->add('rm-css', asset('vendor/core/plugins/religious-merit/css/rm-public.css'), [], [], '1.0.0');
        Theme::asset()
            ->container('footer')
            ->usePath(false)
            ->add(
                'modal-merit-js',
                asset('vendor/core/plugins/religious-merit/js/modal-merit.js'),
                ['jquery'],
                [],
                '1.0.0'
            );

        $query = BaseHelper::stringify($request->input('q'));

        $projects = $religiousMeritProjectRepository->getFinishedProjects($query);

        $data = [
            'view' => 'religious-merit-finished-projects',
            'default_view' => 'plugins/religious-merit::themes.finished-projects',
            'data' => [
                'projects' => $projects
            ]
        ];

        return Theme::layout('no-sidebar')->scope($data['view'], $data['data'], $data['default_view'])->render();
    }

    public function getProjectsInCategory(string $slug, ReligiousMeritProjectService $service)
    {
        $slug = SlugHelper::getSlug($slug, get_projects_category_prefix());

        if (!$slug) {
            abort(404);
        }

        $data = $service->handleFrontRoutes($slug);

        Theme::asset()
            ->usePath(false)
            ->add('rm-css', asset('vendor/core/plugins/religious-merit/css/rm-public.css'), [], [], '1.0.0')
            ->add('rm-slick-css', asset('vendor/core/plugins/religious-merit/css/rm-slick-public.min.css'), [], [], '1.0.0');

        Theme::asset()
            ->container('footer')
            ->usePath(false)
            ->add(
                'rm-slick-public-js',
                asset('vendor/core/plugins/religious-merit/js/rm-slick-public.min.js'),
                ['jquery'],
                [],
                '1.0.0'
            )
            ->add(
                'rm-public-js',
                asset('vendor/core/plugins/religious-merit/js/rm-public.js'),
                ['rm-slick-public-js'],
                [],
                '1.0.0'
            )
            ->add(
                'modal-merit-js',
                asset('vendor/core/plugins/religious-merit/js/modal-merit.js'),
                ['jquery'],
                [],
                '1.0.0'
            );

        return Theme::layout('no-sidebar')->scope($data['view'], $data['data'], $data['default_view'])->render();
    }

    public function getAvailableProjectDetail(string $slug, ReligiousMeritProjectService $service)
    {
        $slug = SlugHelper::getSlug($slug, get_projects_prefix());

        if (!$slug) {
            abort(404);
        }

        $data = $service->handleFrontRoutes($slug);

        Theme::asset()
            ->usePath(false)
            ->add('rm-css', asset('vendor/core/plugins/religious-merit/css/rm-public.css'), [], [], '1.0.0')
            ->add('rm-slick-css', asset('vendor/core/plugins/religious-merit/css/rm-slick-public.min.css'), [], [], '1.0.0');

        Theme::asset()
            ->container('footer')
            ->usePath(false)
            ->add(
                'rm-slick-public-js',
                asset('vendor/core/plugins/religious-merit/js/rm-slick-public.min.js'),
                ['jquery'],
                [],
                '1.0.0'
            )
            ->add(
                'rm-public-js',
                asset('vendor/core/plugins/religious-merit/js/rm-public.js'),
                ['rm-slick-public-js'],
                [],
                '1.0.0'
            )
            ->add(
                'modal-merit-js',
                asset('vendor/core/plugins/religious-merit/js/modal-merit.js'),
                ['jquery'],
                [],
                '1.0.0'
            )->add(
                'modal-merit-effort-js',
                asset('vendor/core/plugins/religious-merit/js/modal-merit-effort.js'),
                ['jquery'],
                [],
                '1.0.0'
            )->add(
                'modal-merit-artifact-js',
                asset('vendor/core/plugins/religious-merit/js/modal-merit-artifact.js'),
                ['jquery'],
                [],
                '1.0.0'
            );

        return Theme::layout('no-sidebar')->scope($data['view'], $data['data'], $data['default_view'])->render();
    }

    public function merit(PublicReligiousMeritRequest $request, ReligiousMeritInterface $religiousMerit, ReligiousMeritProjectInterface $projectRepository, BaseHttpResponse $response)
    {
        $blacklistDomains = setting('blacklist_email_domains');

        if ($blacklistDomains && !empty($request->input('email'))) {
            $emailDomain = Str::after(strtolower($request->input('email')), '@');

            $blacklistDomains = collect(json_decode($blacklistDomains, true))->pluck('value')->all();

            if (in_array($emailDomain, $blacklistDomains)) {
                return $response
                    ->setError()
                    ->setMessage(__('Your email is in blacklist. Please use another email address.'));
            }
        }
        try {
            $member_id = 0;
            $member = auth('member')->user();
            if ($member) {
                $member_id  = $member->id;
            }

            $project = $projectRepository->findById($request->input('project_id'));

            if (!$project) {
                return $response
                    ->setError()
                    ->setMessage(__('Project not found'));
            }

            $type = $request->input('type');
            if (!$type) $type = ReligiousTypeEnum::MONEY;

            $transaction_message = get_project_transaction_message($project->transaction_message_prefix);
            $order = $religiousMerit->getModel();
            $order->fill(array_merge($request->input(), [
                'member_id' => $member_id,
                'transaction_message' => $transaction_message,
                'type' => $type,
                'payment_gate' => $request->input('payment_gate') ?? 'transfer'
            ]));
            $order = $religiousMerit->createOrUpdate($order);

            $payment_url  = '';

            switch ($type) {
                case ReligiousTypeEnum::MONEY:
                    switch ($order->payment_gate) {
                        case PaymentGateTypeEnum::VNPAY:
                            $is_enabled = setting('vnpay_is_enabled');
                            if (!$is_enabled) {
                                return $response
                                    ->setError()
                                    ->setMessage(__("Payment method is not allowed"));
                            }
                            // create vnpay order
                            $return_url = route('public.vnpay.verify-result');
                            $tran_result = create_vnpay_transaction([
                                'name' => $order->name,
                                'target_id' => $order->id,
                                'target_type' => ReligiousMerit::class,
                                'amount' => $order->amount,
                                'language' => 'vi',
                                'ip' => request()->ip(),
                                'order_type' => 'other',
                            ], $return_url);

                            $payment_url = $tran_result['payment_url'];
                            break;
                        case PaymentGateTypeEnum::TRANSFER:
                            $bank_transfer_bank_name = setting('bank_transfer_bank_name');
                            $bank_transfer_bank_account_number = setting('bank_transfer_bank_account_number');
                            $bank_transfer_bank_account_name = setting('bank_transfer_bank_account_name');

                            if (!$bank_transfer_bank_name || !$bank_transfer_bank_account_number || !$bank_transfer_bank_account_name) {
                                return $response
                                    ->setError()
                                    ->setMessage(__("Phương thức thanh toán không được hỗ trợ"));
                            }
                            break;
                    }
                    break;
                case ReligiousTypeEnum::ARTIFACT:
                case ReligiousTypeEnum::EFFORT:
                    $religious_merit_products = [];
                    if ($type == ReligiousTypeEnum::ARTIFACT && !$project->can_contribute_artifact) {
                        return $response
                            ->setError()
                            ->setMessage(__("Đóng góp bằng hiện vật hiện không được hỗ trợ"));
                    }
                    if ($type == ReligiousTypeEnum::EFFORT && !$project->can_contribute_effort) {
                        return $response
                            ->setError()
                            ->setMessage(__("Đóng góp bằng công sức hiện không được hỗ trợ"));
                    }
                    $artifacts = $request->input('merit_products');
                    if ($artifacts == "null") {
                        return $response
                            ->setError()
                            ->setMessage(__("Danh sách đóng góp không được bỏ trống"));
                    }
                    $artifact_ids = array_keys($artifacts);
                    $product_type = $type == ReligiousTypeEnum::ARTIFACT ? ProductTypeEnum::PHYSICAL : ProductTypeEnum::DIGITAL;
                    $projectProducts = $project->products($product_type)->whereIn('id', $artifact_ids)->get();
                    $totalPrice = 0;
                    foreach ($artifacts as $projectProductId => $qty) {
                        if ($qty > 0) {
                            $projectProduct = $projectProducts->first(function ($p) use ($projectProductId) {
                                return $p->id == $projectProductId;
                            });
                            if ($projectProduct) {
                                // Kiểm tra số lượng
                                $availableQty = $projectProduct->qty - ($projectProduct->total_merit_qty || 0);
                                if ($availableQty >= $qty) {
                                    $totalPrice += $projectProduct->product->price;
                                    array_push($religious_merit_products, [
                                        'merit_id' => $order->id,
                                        'merit_project_product_id' => $projectProductId,
                                        'product_name' => $projectProduct->product->name,
                                        'qty' => $qty,
                                        'price' => $projectProduct->product->price,
                                        'product_type' => $projectProduct->product_type,
                                    ]);
                                }
                            }
                        }
                    }
                    if (count($religious_merit_products)) {
                        ReligiousMeritProduct::insert($religious_merit_products);
                        $religiousMerit->createOrUpdate([
                            'amount' => $totalPrice,
                            'status' => ReligiousMeritStatusEnum::IS_BOOKED,
                        ], [
                            'id' => $order->id
                        ]);
                    }
                    break;
                default:
                    # code...
                    break;
            }

            Session::put('last_merit_id', $order->id);
            return $response->setData([
                'transaction_message' => $transaction_message,
                'order_id' => $order->id,
                'date' => $order->created_at->format('ymd'),
                'amount' => $order->amount,
                'message' => (__('Order is created')),
                'payment_url' => $payment_url,
            ]);
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(__($exception->getMessage()));
        }
    }

    public function getProjectMerits(Request $request, ReligiousMeritProjectInterface $religiousMeritProjectRepository)
    {
        $projectId = $request->project_id;
        $search = $request->query('search');
        $type = $request->query('type');
        $merits = $religiousMeritProjectRepository->getProjectMerits($projectId, $search, $type);

        return view('plugins/religious-merit::partials.table-merit', ['merits' => $merits]);
    }

    public function getProjectBudgets(Request $request, ReligiousMeritProjectInterface $religiousMeritProjectRepository, ReligiousMeritProjectService $service)
    {
        $projectId = $request->project_id;
        $search = $request->query('search');
        $page = $request->query('page') ? (int) $request->query('page') : 1;
        $project = $religiousMeritProjectRepository->getAvailableProjectDetail($projectId);

        $budgetData = $service->getProjectBudget($project, $search, $page);


        return view('plugins/religious-merit::partials.table-budget', [
            ...$budgetData
        ]);
    }

    public function getProjectEfforts(Request $request, ReligiousMeritProjectInterface $religiousMeritProjectRepository, ReligiousMeritProjectService $religiousMeritProjectService)
    {
        $projectId = $request->project_id;
        $search = $request->query('search');
        $page = $request->query('page');
        $project = $religiousMeritProjectRepository->getAvailableProjectDetail($projectId);
        $efforts = $religiousMeritProjectService->getProjectEfforts($project->digital_products->toArray(), $search, $page);

        return view('plugins/religious-merit::partials.table-effort', [...$efforts]);
    }

    public function getProjectArtifacts(Request $request, ReligiousMeritProjectInterface $religiousMeritProjectRepository, ReligiousMeritProjectService $religiousMeritProjectService)
    {
        $projectId = $request->project_id;
        $search = $request->query('search');
        $page = $request->query('page');
        $project = $religiousMeritProjectRepository->getAvailableProjectDetail($projectId);
        $artifacts = $religiousMeritProjectService->getProjectArtifacts($project->physical_products->toArray(), $search, $page);

        return view('plugins/religious-merit::partials.table-artifact', [...$artifacts]);
    }

    public function uploadTransactionImage(Request $request, ReligiousMeritInterface $meritRepository, BaseHttpResponse $response)
    {
        $last_merit_id = $request->input('merit_id') ?? Session::get('last_merit_id');
        if (!$last_merit_id || !request()->file('file')) {
            return $response
                ->setError()
                ->setMessage(__("Không tìm thấy dữ liệu cần xử lý"))->toApiResponse();
        }

        $merit = $meritRepository->getModel()->where([
            ['id', '=', $last_merit_id],
            ['status', '=', ReligiousMeritStatusEnum::IN_PROGRESS]
        ])->first();

        if (!$merit) {
            return $response
                ->setError()
                ->setMessage(__("Không tìm thấy bản ghi hoặc bản ghi không đúng trạng thái"))
                ->toApiResponse();
        }

        $currentDate = Carbon::now();
        $folder = 'transaction-images/' . $currentDate->year . '/' . $currentDate->month . '/' . $currentDate->day;

        try {
            $result = RvMedia::handleUpload(request()->file('file'), 0, $folder);
            if ($result['error']) {
                return $response->setError()->setMessage($result['message'])->toApiResponse();
            }

            if ($merit->transaction_image_id) $this->fileRepository->forceDelete(['id' => $merit->transaction_image_id]);

            $file = $result['data'];
            $merit->transaction_image_id = $file->id;
            $merit->save();

            return $response
                ->setMessage(__('Upload thành công'))
                ->setData(['url' => RvMedia::url($file->url)])
                ->toApiResponse();
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage())
                ->toApiResponse();
        }
    }

    public function getProjectReport(Request $request, ReligiousMeritProjectInterface $religiousMeritProjectRepository, ReligiousMeritProjectService $service)
    {
        $projectId = $request->project_id;
        $search = $request->query('search_budget');
        $page = $request->query('page') ? (int) $request->query('page') : 1;
        $project = $religiousMeritProjectRepository->getAvailableProjectDetail($projectId);
        $budgetData = $service->getProjectBudget($project, $search, $page);

        $query = $request->query('search_merit');
        $merits = $religiousMeritProjectRepository->getProjectMerits($projectId, $query);

        return [
            'budgets' => view('plugins/religious-merit::partials.table-budget', [
                ...$budgetData
            ])->render(),
            'merits' => view('plugins/religious-merit::partials.table-merit', ['merits' => $merits])->render()
        ];
    }

    public function exportProjectReport(Request $request, ReligiousMeritProjectInterface $religiousMeritProjectRepository, ReligiousMeritProjectService $religiousMeritProjectService) {
        $projectId = $request->project_id;
        $project = $religiousMeritProjectRepository->getAvailableProjectDetail($projectId);
        $merits = $religiousMeritProjectRepository->getProjectMerits($projectId, '', '', '', 999);
        $budgetsOrigin = $religiousMeritProjectService->getProjectBudget($project, '', 1, 999)['budgets'];
        $budgets = array();
        for ($i = 0; $i < count($budgetsOrigin); $i++) {
            $items = array();
            $qty = 0;
            $totalSpent = 0;

            for ($j = 0; $j < count($budgetsOrigin[$i]); $j++) {
                $key = $budgetsOrigin[$i][$j]['key'];
                $value = $budgetsOrigin[$i][$j]['value'];

                $items[$key] = $value;

                if ($key === 'qty') { $qty = $value; }
                if ($key === 'total_spent') { $totalSpent = $value; }
                if ($key === 'cost_per_unit') { $items['into_money'] = $value * $qty; }
                if ($key === 'status') { 
                    if ($value == 1) {
                        $items[$key] = 'Đã hoàn thành';
                    }else if ($totalSpent > 0) {
                        $items[$key] = 'Đã chi: ' . $totalSpent;
                    }else {
                        $items[$key] = 'Chờ triển khai';
                    }
                }
            }

            $items = array_diff_key($items, array_flip(['cost_per_unit', 'total_spent']));
            array_push($budgets, $items);
        }

        return Excel::download(new ProjectReportExport((object) ['budgets' => $budgets, 'merits' => $merits]), 'excel.xlsx');
    }
}
