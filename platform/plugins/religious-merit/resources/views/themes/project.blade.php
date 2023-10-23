@if (!empty($project))
@php
$galleries = [];
if ($project && gallery_meta_data($project)) {
$galleries = gallery_meta_data($project);
}
@endphp

<div class="bg-white">
    <div class="container pt-3">
        {!! Theme::breadcrumb()->render() !!}
    </div>
</div>
<div style="background: #e4dec3" class="pt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6 col-md-12">
                <div class="project-gallery">
                    @if (!empty($galleries))
                    <div class="slider-for mb-3">
                        @foreach ($galleries as $gallery)
                        <img src="{{ RvMedia::getImageUrl(Arr::get($gallery, 'img')) }}" alt="{{ $project->name }}" loading="lazy">
                        @endforeach
                    </div>
                    <div class="slider-nav">
                        @foreach ($galleries as $gallery)
                        <img src="{{ RvMedia::getImageUrl(Arr::get($gallery, 'img')) }}" alt="{{ $project->name }}" loading="lazy">
                        @endforeach
                    </div>
                    @elseif ($project->image)
                    <img src="{{ RvMedia::getImageUrl($project->image, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $project->name }}" loading="lazy" class="project-gallery-single">
                    @endif
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <h3 class="mt-3">{{ $project->name }}</h3>
                <span class="d-block mb-4">Ngày kết thúc: {{ BaseHelper::formatDate($project->to_date, 'd/m/Y') }}</span>
                <div class="project-item-content">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="project-item-content-amount">{{ currency_format($project->current_amount) }}</span>
                        <span class="project-item-content-percent">{{ currency_format($project->current_percent_merit, '%') }}</span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" style="width: {{ $project->current_percent_merit }}%" aria-valuenow="{{ $project->current_percent_merit }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex gap-3 justify-content-between mb-4">
                        <div>
                            Mục tiêu: <span class="project-item-content-expect">{{ currency_format($project->total_expected_amount) }}</span>
                        </div>
                        <div>
                            <img src="/vendor/core/plugins/religious-merit/images/dollar.png" alt="dollar" height="18" class="mb-2">
                            <span class="project-item-content-merits">{{ $project->total_merits }}</span>
                            Lượt Đóng Góp
                        </div>
                    </div>
                    <div class="row project-item-content-contribute">
                        @if ($project->expectation_amount)
                        <div class="col-4">
                            <p>Số tiền</p>
                            <span>{{ currency_format($project->money_current_amount) }}</span>
                        </div>
                        @endif
                        @if ($project->can_contribute_effort)
                        <div class="col-4">
                            <p>Ngày công</p>
                            <span>{{ currency_format($project->digital_products_current_amount) }}</span>
                        </div>
                        @endif
                        @if ($project->can_contribute_artifact)
                        <div class="col-4">
                            <p>Hiện Vật</p>
                            <span>{{ currency_format($project->physical_products_current_amount) }}</span>
                        </div>
                        @endif
                    </div>
                    @if (!$project->is_finished)
                        <div class="row mt-3">
                            <div class="col-4">
                                <button class="btn btn-primary w-100 text-nowrap merit-action" data-project="{{ $project->id }}">Đóng góp</button>
                            </div>
                            @if ($project->can_contribute_effort)
                            <div class="col-4">
                                <button class="btn btn-primary w-100 text-nowrap merit-effort-action" data-project="{{ $project->id }}">Công sức</button>
                            </div>
                            @endif
                            @if ($project->can_contribute_artifact)
                            <div class="col-4">
                                <button class="btn btn-primary w-100 text-nowrap merit-artifact-action" data-project="{{ $project->id }}">Hiện vật</button>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="row mt-3">
                            <div class="col-4">
                                <button class="btn btn-primary mb-0 merit-report-btn w-100" data-project="{{ $project->id }}">Báo cáo</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="project-tabs mt-5">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item mr-2" role="presentation">
                    <button class="nav-link px-4 py-3 active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content" type="button" role="tab" aria-controls="content" aria-selected="true">Nội dung</button>
                </li>
                <li class="nav-item mx-2" role="presentation">
                    <button class="nav-link px-4 py-3" id="budget-tab" data-bs-toggle="tab" data-bs-target="#budget" type="button" role="tab" aria-controls="budget" aria-selected="false">Dự trù kinh phí</button>
                </li>
                @if ($project->can_contribute_effort)
                <li class="nav-item mx-2" role="presentation">
                    <button class="nav-link px-4 py-3" id="effort-tab" data-bs-toggle="tab" data-bs-target="#effort" type="button" role="tab" aria-controls="effort" aria-selected="false">Công sức</button>
                </li>
                @endif
                @if ($project->can_contribute_artifact)
                <li class="nav-item mx-2" role="presentation">
                    <button class="nav-link px-4 py-3" id="artifact-tab" data-bs-toggle="tab" data-bs-target="#artifact" type="button" role="tab" aria-controls="artifact" aria-selected="false">Hiện vật</button>
                </li>
                @endif
                <li class="nav-item ml-2" role="presentation">
                    <button class="nav-link px-4 py-3" id="list-tab" data-bs-toggle="tab" data-bs-target="#list" type="button" role="tab" aria-controls="list" aria-selected="false">Danh sách đóng góp</button>
                </li>
            </ul>

        </div>
    </div> <!-- đóng container để mở container-fluid -->
    <div class="container-fluid project-tabs bg-body px-0 pb-5">
        <div class="container"><!-- mở container-fluild -->
            <div class="tab-content py-5" id="myTabContent">
                <div class="tab-pane fade show active" id="content" role="tabpanel" aria-labelledby="content-tab">
                    <div class="row">
                        <div class="col-lg-8 col-md-12">
                            <div class="mb-4">
                                {!! BaseHelper::clean($project->content) !!}
                            </div>
                            <p class="text-black text-share mb-2">Chia sẻ dự án</p>
                            <div class="d-inline-block">{!! Theme::partial('button-share', ['post' => $project ]) !!}</div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            {!! dynamic_sidebar('project_detail') !!}
                        </div>
                    </div>
                    @if ($projectsRelated->count() > 0)
                    <h3 class="h3 text-center text-primary mt-5 mb-4">Các dự án khác</h3>
                    <div class="row g-4 project mb-4">
                        @foreach ($projectsRelated as $projectsRelated)
                        @include('plugins/religious-merit::themes.templates.project', [
                        'project' => $projectsRelated,
                        ])
                        @endforeach
                    </div>
                    <div class="text-center">
                        <a href="/<?= get_projects_prefix() ?>/dang-trien-khai" title="Xem tất cả"><button class="btn btn-primary" style="width: 200px">Xem tất cả</button></a>
                    </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="budget" role="tabpanel" aria-labelledby="budget-tab">
                    <div class="input-group mb-4">
                        <span class="input-group-text bg-white border-white" id="input-search-category-name">
                            <img src="/vendor/core/plugins/religious-merit/images/search.png" alt="search">
                        </span>
                        <input type="text" class="form-control border-white input-search input-search-budgets bg-white" placeholder="Tên hạng mục" aria-label="Tên hạng mục" aria-describedby="input-search-category-name">
                    </div>
                    <div id="table-budgets" data-project-id="{{ $project->id }}"></div>
                </div>
                <div class="tab-pane fade" id="effort" role="tabpanel" aria-labelledby="effort-tab">
                    <div class="input-group mb-4">
                        <span class="input-group-text bg-white border-white">
                            <img src="/vendor/core/plugins/religious-merit/images/search.png" alt="search">
                        </span>
                        <input type="text" class="form-control border-white input-search input-search-efforts bg-white" placeholder="Loại công sức" aria-label="Loại công sức" aria-describedby="input-search-category-name">
                    </div>
                    <div id="table-efforts" data-project-id="{{ $project->id }}"></div>
                </div>
                <div class="tab-pane fade" id="artifact" role="tabpanel" aria-labelledby="artifact-tab">
                    <div class="input-group mb-4">
                        <span class="input-group-text bg-white border-white">
                            <img src="/vendor/core/plugins/religious-merit/images/search.png" alt="search">
                        </span>
                        <input type="text" class="form-control border-white input-search input-search-artifacts bg-white" placeholder="Tên hiện vật" aria-label="Tên hiện vật" aria-describedby="input-search-category-name">
                    </div>
                    <div id="table-artifacts" data-project-id="{{ $project->id }}"></div>
                </div>
                <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="list-tab">
                    <div class="row gx-4 gy-3 mb-4">
                        <div class="col-12 col-sm-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-white" id="input-search-merit-person">
                                    <img src="/vendor/core/plugins/religious-merit/images/search.png" alt="search">
                                </span>
                                <input type="text" class="form-control border-white input-search input-search-merits bg-white" placeholder="Người Đóng Góp" aria-label="Người Đóng Góp" aria-describedby="input-search-merit-person">
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <select class="form-select select-search select-search-merits">
                                <option value="">Lọc theo</option>
                                <option value="{{ \Cmat\ReligiousMerit\Enums\PaymentGateTypeEnum::CASH }}">Tiền mặt</option>
                                <option value="{{ \Cmat\ReligiousMerit\Enums\PaymentGateTypeEnum::TRANSFER }}">Chuyển khoản</option>
                                <option value="{{ \Cmat\ReligiousMerit\Enums\ReligiousTypeEnum::EFFORT }}">Công sức</option>
                                <option value="{{ \Cmat\ReligiousMerit\Enums\ReligiousTypeEnum::ARTIFACT }}">Hiện vật</option>
                            </select>
                        </div>
                    </div>
                     <div id="table-merits" data-project-id="{{ $project->id }}"></div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- đóng container-fluid -->
<div class="container"> <!-- mở lại container -->
</div>
</div>
@endif

@include('plugins/religious-merit::partials.modal-merit', [
'type' => '',
'name' => 'modal-merit',
'title' => trans('plugins/religious-merit::religious-merit.select_payment_gate'),
'submit_text' => trans('plugins/religious-merit::religious-merit.merit'),
'action_button_attributes' => [
'class' => 'delete-crud-entry',
],
])

@include('plugins/religious-merit::partials.modal-merit-effort', [
    'type' => '',
    'name' => 'modal-merit-effort',
    'title' => 'Ngày công cần Công Đức',
    'submit_text' => trans('plugins/religious-merit::religious-merit.merit'),
    'efforts' => $project->digital_products,
])

@include('plugins/religious-merit::partials.modal-merit-artifact', [
    'type' => '',
    'name' => 'modal-merit-artifact',
    'title' => 'Hiện vật cần Công Đức',
    'submit_text' => trans('plugins/religious-merit::religious-merit.merit'),
    'artifacts' => $project->physical_products,
])

@include('plugins/religious-merit::partials.modal-merit-report', [
    'type' => '',
    'name' => 'modal-merit-report',
    'title' => trans('plugins/religious-merit::religious-merit.report'),
    'download_text' => trans('plugins/religious-merit::religious-merit.download-report'),
    'project_id' => $project->id,
])


