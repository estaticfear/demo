{!! Theme::partial('banner-top') !!}
<div class="container pt-4 pb-5">
    <ul class="projects-tabs d-flex gap-5 mb-5">
        <li><a href="/<?= get_projects_prefix() ?>/dang-trien-khai" class="fs-4 text-primary">Đang triển khai</a></li>
        <li><a href="/<?= get_projects_prefix() ?>/da-ket-thuc" class="fs-4 text-primary active fw-bold">Đã kết thúc</a></li>
    </ul>
    <div class="text-center mb-5">
        <h3 class="h3 text-primary">Dự án đã kết thúc</h3>
        <p class="fs-6">Các dự án đã hết thời gian Đóng Góp</p>
    </div>
    @include('plugins/religious-merit::themes.templates.projects', compact(['projects']))
</div>
