<?php

namespace Cmat\ReligiousMerit\Tables;

use Cmat\ReligiousMerit\Exports\MeritProjectsExport;
use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;
use Cmat\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;
use Html;

class ReligiousMeritProjectTable extends TableAbstract
{
    protected $hasActions = true;

    protected $hasFilter = true;

    protected string $exportClass = MeritProjectsExport::class;

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ReligiousMeritProjectInterface $religiousMeritRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $religiousMeritRepository;

        if (!Auth::user()->hasAnyPermission(['religious-merit-project.edit', 'religious-merit-project.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                $html = '';
                if (!Auth::user()->hasPermission('religious-merit-project.edit')) {
                    $html .= BaseHelper::clean($item->name);
                }
                $html .= Html::link(route('religious-merit-project.edit', $item->id), BaseHelper::clean($item->name));

                if ($item->can_contribute_effort || $item->can_contribute_artifact) {
                    $html .= '<div class="my-2">';
                }
                if ($item->can_contribute_effort) {
                    $html .= '<b style="background-color: #323178; color: #fff;padding: 4px 8px 3px 8px;border-radius: 3px; margin-right: 5px">Công sức</b>';
                }
                if ($item->can_contribute_artifact) {
                    $html .= '<b style="background-color: #323178; color: #fff;padding: 4px 8px 3px 8px;border-radius: 3px">Hiện vật</b>';
                }
                if ($item->can_contribute_effort || $item->can_contribute_artifact) {
                    $html .= '</div>';
                }
                return $html;
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->start_date, 'd/m/Y') . ' - ' . BaseHelper::formatDate($item->to_date, 'd/m/Y');
            })
            ->editColumn('current_amount', function ($item) {
                return $item->current_amount ? '<b style="background-color: green; color: #fff;padding: 4px 8px 3px 8px;border-radius: 3px">' . currency_format($item->current_amount) . '</b>' : '';
            })
            ->editColumn('expectation_amount', function ($item) {
                return '<b style="background-color: #6659d5; color: #fff;padding: 4px 8px 3px 8px;border-radius: 3px">' . currency_format($item->expectation_amount) . '</b>';
            })
            ->editColumn('progress', function ($item) {
                return '' . number_format($item->current_amount / $item->expectation_amount * 100, 2) . ' %';
            })
            ->editColumn('project_category_id', function ($item) {
                return $item->category ? $item->category->name : '';
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('religious-merit-project.edit', 'religious-merit-project.destroy', $item);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'name',
                'created_at',
                'current_amount',
                'expectation_amount',
                'status',
                'is_featured',
                'start_date',
                'to_date',
                'project_category_id',
                'can_contribute_effort',
                'can_contribute_artifact'
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            // 'id' => [
            //     'title' => trans('core/base::tables.id'),
            //     'width' => '20px',
            // ],
            'name' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'current_amount' => [
                'title' => trans('plugins/religious-merit::religious-merit-project.current-amount'),
                'class' => 'text-start',
            ],
            'expectation_amount' => [
                'title' => trans('plugins/religious-merit::religious-merit-project.expectation-amount'),
                'class' => 'text-start',
            ],
            'progress' => [
                'title' => trans('plugins/religious-merit::religious-merit.progress'),
                'class' => 'text-start',
                'width' => '80px',
            ],
            'is_featured' => [
                'title' => trans('core/base::forms.is_featured'),
                'class' => 'text-start',
                'width' => '50px',
            ],
            'project_category_id' => [
                'title' => trans('plugins/religious-merit::religious-merit.category'),
                'class' => 'text-start',
                'width' => '80px',
            ],
            'created_at' => [
                'title' => __('Thời gian'),
                'width' => '100px',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('religious-merit-project.create'), 'religious-merit-project.create');
    }

    public function bulkActions(): array
    {
        return [];
//        return $this->addDeleteAction(route('religious-merit-project.deletes'), 'religious-merit-project.destroy', parent::bulkActions());
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }

    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }

    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }
}
