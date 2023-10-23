<section class="section page-info__banner position-relative"
    data-background="{{ Theme::get('breadcrumbBannerImage') ?: (theme_option('default_breadcrumb_banner_image') ? RvMedia::getImageUrl(theme_option('default_breadcrumb_banner_image')) : Theme::asset()->url('images/banner-text-page.jpeg')) }}">
    <div class="section container">
        <div class="page-info position-absolute text-center text-capitalize">
            <h1 class="page-info__title h2">{!! BaseHelper::clean(Theme::get('section-name') ?: SeoHelper::getTitle()) !!}</h1>
            <h2 class="page-info__description h4">{!! SeoHelper::getDescription() !!}</h2>
        </div>
    </div>
</section>
