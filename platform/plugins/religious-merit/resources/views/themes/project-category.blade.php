{!! Theme::partial('banner-top') !!}
<div class="container pt-4 pb-5">
    <div class="text-center mb-5">
        <h1 class="h3 text-primary">{{ $category->name }}</h1>
        {{-- <p class="fs-6">Các dự án đang được quan tâm nhiều nhất</p> --}}
    </div>
    @include('plugins/religious-merit::themes.templates.projects', compact(['projects', 'category']))
</div>
