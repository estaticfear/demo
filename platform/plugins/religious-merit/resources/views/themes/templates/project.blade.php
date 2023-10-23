@if (!empty($project))
<div class="col-lg-4 col-md-6 col-sm-12">
    <div class="project-item p-3 bg-white">
        <div class="project-item-image mb-3">
            <img src="{{ RvMedia::getImageUrl($project->image, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $project->name }}" loading="lazy">
            <a href="@if ($project->slug) /<?= get_projects_prefix() ?>/{{ $project->slug }} @endif" title="{{ $project->name }}"></a>
        </div>
        <h3 class="project-item-title mb-3">
            <a href="@if ($project->slug) /<?= get_projects_prefix() ?>/{{ $project->slug }} @endif" title="{{ $project->name }}">{{ $project->name }}</a>
        </h3>
        <div class="project-item-content">
            <div class="d-flex justify-content-between mb-2 font-weight-bold">
                <span class="project-item-content-amount">{{ currency_format($project->current_amount) }}</span>
                <span class="project-item-content-percent">{{ currency_format($project->current_percent_merit, '%') }}</span>
            </div>
            <div class="progress mb-3">
                <div class="progress-bar" role="progressbar" style="width: {{ $project->current_percent_merit }}%" aria-valuenow="{{ $project->current_percent_merit }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex gap-4 justify-content-between">
                <div class="w-50">
                    Mục tiêu: <span class="project-item-content-expect">{{ currency_format($project->total_expected_amount) }}</span>
                </div>
                <div class="w-50 text-end">
                    <img src="/vendor/core/plugins/religious-merit/images/dollar.png" alt="dollar" height="18" class="mb-2">
                    <span class="project-item-content-merits">{{ $project->total_merits }}</span>
                    Lượt Đóng Góp
                </div>
            </div>
        </div>
        <div class="project-item-button d-flex gap-4 justify-content-between mt-3">
            <div class="w-50">
                {!! Theme::partial('button-share', ['post' => $project ]) !!}
            </div>
            @if (!$project->is_finished)
                <button class="btn btn-primary w-50 mb-0 merit-action" data-project="{{ $project->id }}">Đóng góp</button>
            @else
                <button class="btn btn-primary w-50 mb-0 merit-report-btn" data-project="{{ $project->id }}">Báo cáo</button>
            @endif

        </div>
    </div>
</div>
@endif
