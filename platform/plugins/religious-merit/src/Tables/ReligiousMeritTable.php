<?php

namespace Cmat\ReligiousMerit\Tables;

use Cmat\ReligiousMerit\Enums\PaymentGateTypeEnum;
use Cmat\ReligiousMerit\Enums\ReligiousMeritStatusEnum;
use Cmat\ReligiousMerit\Exports\MeritsExport;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritInterface;
use Cmat\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use Html;
use RvMedia;

class ReligiousMeritTable extends TableAbstract
{
    protected $hasActions = true;

    protected $hasFilter = true;

    protected string $exportClass = MeritsExport::class;

    public function __construct(
        DataTables                               $table,
        UrlGenerator                             $urlGenerator,
        ReligiousMeritInterface                  $religiousMeritRepository,
        protected ReligiousMeritProjectInterface $religiousMeritProjectRepository
    )
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $religiousMeritRepository;

        if (!Auth::user()->hasAnyPermission(['religious-merit.edit', 'religious-merit.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                $name = '';
                $transactionImage = $item->transactionImage->url ? RvMedia::url($item->transactionImage->url) : null;
                if (!Auth::user()->hasPermission('religious-merit.edit')) {
                    $name = BaseHelper::clean($item->name);
                }
                $name = Html::link(route('religious-merit.edit', $item->id), BaseHelper::clean($item->name));
                $html = '<div><div>' . $name . '</div>';
                // Người dùng
                if ($item->member) {
                    if (!Auth::user()->hasPermission('member.edit')) {
                        $html .= '<div>' . BaseHelper::clean($item->member->email) . '</div>';
                    } else {
                        $html .= '<div>Member: ' . Html::link(route('member.edit', $item->member->id), BaseHelper::clean($item->member->email)) . '</div>';
                    }
                }
                // Ảnh giao dịch
                if ($transactionImage) $html .= '<div><a class="badge badge-pill badge-warning" target="_blank" href="' . $transactionImage . '">Ảnh giao dịch</a></div>';
                $html .= '</div>';
                return $html;
            })
            // ->editColumn('member_id', function ($item) {
            //     if ($item->member) {
            //         if (!Auth::user()->hasPermission('member.edit')) {
            //             return BaseHelper::clean($item->member->email);
            //         }
            //         return Html::link(route('member.edit', $item->member->id), BaseHelper::clean($item->member->email));
            //     }
            //     return '';
            // })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('transaction_message', function ($item) {
                return $item->transaction_message;
            })
            ->editColumn('project_id', function ($item) {
                $html = '';
                if (!Auth::user()->hasPermission('religious-merit-project.edit')) {
                    $html .= BaseHelper::clean($item->project->name);
                }
                $html .= Html::link(route('religious-merit-project.edit', $item->project->id), BaseHelper::clean($item->project->name));

                if ($item->project->can_contribute_effort || $item->project->can_contribute_artifact) {
                    $html .= '<div class="my-2">';
                }
                if ($item->project->can_contribute_effort) {
                    $html .= '<b style="background-color: #323178; color: #fff;padding: 4px 8px 3px 8px;border-radius: 3px; margin-right: 5px">Công sức</b>';
                }
                if ($item->project->can_contribute_artifact) {
                    $html .= '<b style="background-color: #323178; color: #fff;padding: 4px 8px 3px 8px;border-radius: 3px">Hiện vật</b>';
                }
                if ($item->project->can_contribute_effort || $item->project->can_contribute_artifact) {
                    $html .= '</div>';
                }
                return $html;
            })
            ->editColumn('amount', function ($item) {
                return '<b style="background-color: green; color: #fff;padding: 4px 8px 3px 8px;border-radius: 3px">' . currency_format($item->amount) . '</b>';
            })
            ->editColumn('type', function ($item) {
                return $item->type->toHtml();
            })
            ->editColumn('payment_gate', function ($item) {
                return $item->payment_gate->toHtml();
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                if ($item->payment_gate == PaymentGateTypeEnum::TRANSFER()) {
                    return $this->getOperations('religious-merit.edit', 'religious-merit.destroy', $item);
                }

                return $this->getOperations('religious-merit.edit', '', $item);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->repository->getModel()
            ->with([
                'project',
                'member',
                'transactionImage'
            ])
            ->select([
                'id',
                'name',
                'created_at',
                'type',
                'payment_gate',
                'amount',
                'status',
                'project_id',
                'member_id',
                'transaction_image_id',
                'transaction_message'
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
                'orderable' => false,
            ],
            // 'member_id' => [
            //     'title' => trans('plugins/religious-merit::religious-merit.member'),
            //     'class' => 'text-start',
            // ],
            'transaction_message' => [
                'title' => trans('plugins/religious-merit::religious-merit.transaction_message'),
                'class' => 'text-start',
                'orderable' => false,
            ],
            'project_id' => [
                'title' => trans('plugins/religious-merit::religious-merit.project'),
                'class' => 'text-start',
                'orderable' => false,
            ],
            'type' => [
                'title' => 'Type',
                'class' => 'text-start',
                'orderable' => false,
            ],
            'amount' => [
                'title' => trans('plugins/religious-merit::religious-merit.amount'),
                'class' => 'text-start',
                'orderable' => false,
            ],
            'payment_gate' => [
                'title' => trans('plugins/religious-merit::religious-merit.payment_gate'),
                'class' => 'text-start',
                'orderable' => false,
            ],
            'status' => [
                'title' => trans('plugins/religious-merit::religious-merit.status'),
                'class' => 'text-start',
                'orderable' => false,
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('religious-merit.create'), 'religious-merit.create');
    }

    public function bulkActions(): array
    {
        return [];
//        return $this->addDeleteAction(route('religious-merit.deletes'), 'religious-merit.destroy', parent::bulkActions());
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'project_id' => [
                'title' => trans('plugins/religious-merit::religious-merit.project'),
                'type' => 'select-search',
                'validate' => 'required',
                'callback' => 'getProjects'
            ],
            'status' => [
                'title' => trans('plugins/religious-merit::religious-merit.status'),
                'type' => 'customSelect',
                'choices' => ReligiousMeritStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', ReligiousMeritStatusEnum::values()),
            ],
            'payment_gate' => [
                'title' => trans('plugins/religious-merit::religious-merit.payment_gate'),
                'type' => 'customSelect',
                'choices' => PaymentGateTypeEnum::labels(),
                'validate' => 'required|in:' . implode(',', PaymentGateTypeEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }

    public function getProjects(): array
    {
        return $this->religiousMeritProjectRepository->pluck('name', 'id');
    }

    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }

    /**
     * Apply default filter is without fail order
     * @param Relation|Builder|Collection|QueryBuilder $query
     * @return Builder|QueryBuilder|Relation|Collection
     */
    protected function applyScopes(Relation|Builder|Collection|QueryBuilder $query): Builder|QueryBuilder|Relation|Collection
    {
        $request = request();

        $query = parent::applyScopes($query);

        if (!$request->has('filter_columns')) {
            $query = $query->where('status', '!=', ReligiousMeritStatusEnum::FAIL());
        }
        return $query;
    }

    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }
}
