@if (!empty($projects) && ($projects->count()))
    <section class="section-project-need-merit container py-4 my-4">
        <h2 class="section-main-title text-center h3">{{ $title }}</h2>
        <span
            class="section-sub-title text-center sub-title-header-text d-flex justify-content-center">{{ $sub }}</span>
        <div class="section-project-need-merit-content">
            @include('plugins/religious-merit::themes.templates.projects', compact(['projects']))
        </div>
    </section>
@endif
