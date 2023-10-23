<?php if (!empty($project)) : ?>
<div class="modal fade merit-report {{ $name }} fs-6" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header px-4 bg-{{ $type }}">
                <h4 class="modal-title text-primary"><i class="til_img"></i><strong>{{ $title }}</strong></h4>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('public.religious-merit-project.export-project-report', $project->id) }}" class="btn btn-primary w-100 text-nowrap mb-0">Tải báo cáo</a>
                    <button type="button" class="btn-close merit-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
            </div>
            {!! Form::open(['route' => 'public.religious-merit.merit', 'class' => 'modal-body d-none', 'id' => 'merit-report-form']) !!}
            {!! Form::close() !!}
            <section id="project-report" class="container p-4 project-tabs" style="background-color: #f3f3f3">
                <div class="row report-table-budgets">
                    <div class="col-12">
                        <h2 class="h4 mb-3">Dự trù kinh phí</h2>
                        <div class="input-group mb-4">
                            <span class="input-group-text bg-white border-white" id="input-search-category-name">
                                <img src="/vendor/core/plugins/religious-merit/images/search.png" alt="search">
                            </span>
                            <input type="text" class="form-control border-white input-search input-search-budgets bg-white" placeholder="Tên hạng mục" aria-label="Tên hạng mục" aria-describedby="input-search-category-name">
                        </div>
                        <div id="report-table-budgets"></div>
                    </div>
                </div>
                <div class="row report-table-merits">
                    <div class="col-12">
                        <h2 class="h4 mb-3">Danh sách đóng góp</h2>
                        <div class="input-group mb-4">
                            <span class="input-group-text bg-white border-white" id="input-search-merit-person">
                                <img src="/vendor/core/plugins/religious-merit/images/search.png" alt="search">
                            </span>
                            <input type="text" class="form-control border-white input-search input-search-merits bg-white" placeholder="Người Đóng Góp" aria-label="Người Đóng Góp" aria-describedby="input-search-merit-person">
                        </div>
                        <div id="report-table-merits"></div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<?php endif ?>
