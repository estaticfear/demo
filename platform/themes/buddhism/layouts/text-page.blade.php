{!! Theme::partial('header') !!}
@if (Theme::get('section-name'))
{!! Theme::partial('banner-text-page') !!}
@endif
<section class="text-page__content container page-section">
    {!! Theme::content() !!}
</section>
{!! Theme::partial('footer') !!}
