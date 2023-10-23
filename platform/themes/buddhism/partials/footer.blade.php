</div>
<footer class="page-footer pt-50">
    <div class="container">
        <div class="row">
            @if (theme_option('address') || theme_option('website') || theme_option('contact_email') || theme_option('site_description'))
            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                <aside class="widget widget--transparent widget__footer widget__about">
                    <div class="widget__header">
                        <h3 class="widget__title">{{ __('About us') }}</h3>
                    </div>
                    <div class="widget__content">
                        <a href="{{ route('public.single') }}">
                            @if (theme_option('logo'))
                                <div class="mb-3">
                                    <img src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" alt="{{ theme_option('site_title') }}" height="42">
                                    <span class="text-uppercase text-light text-logo">Văn hóa phật giáo Việt Nam</span>
                                </div>
                            @endif
                        </a>
                        <p>{{ theme_option('site_description') }}</p>
                    </div>
                </aside>
            </div>
            @endif
            {!! dynamic_sidebar('footer_sidebar') !!}
            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                <aside class="widget widget--transparent widget__footer widget__about">
                    <div class="person-detail">
                        @if (theme_option('address'))
                        <p><i class="ion-home"></i>{!! theme_option('address') !!}</p>
                        @endif
                        @if (theme_option('website'))
                        <p><i class="ion-earth"></i><a href="{{ theme_option('website') }}">{{ theme_option('website') }}</a></p>
                        @endif
                        @if (theme_option('contact_email'))
                        <p><i class="ion-email"></i><a href="mailto:{{ theme_option('contact_email') }}">{{ theme_option('contact_email') }}</a></p>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </div>
    <div class="page-footer__bottom">
        <div class="container text-center">
            <span>{!! BaseHelper::clean(theme_option('copyright')) !!}</span>
        </div>
    </div>
</footer>
<div id="back2top"><i class="fa fa-angle-up"></i></div>

<!-- JS Library-->
{!! Theme::footer() !!}

</body>
</html>
