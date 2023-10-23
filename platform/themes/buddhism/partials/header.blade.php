<!DOCTYPE html>
<!--[if IE 7]><html class="ie ie7" lang="{{ app()->getLocale() }}"><![endif]-->
<!--[if IE 8]><html class="ie ie8" lang="{{ app()->getLocale() }}"><![endif]-->
<!--[if IE 9]><html class="ie ie9" lang="{{ app()->getLocale() }}"><![endif]-->
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1" name="viewport"/>
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-capable" content="yes">

        {!! BaseHelper::googleFonts('https://fonts.googleapis.com/css2?family=' . urlencode(theme_option('primary_font', 'Roboto')) . ':wght@400;500;700&&display=swap') !!}

        <style>
            :root {
                --color-1st: {{ theme_option('primary_color', '#AF0F26') }};
                --primary-font: '{{ theme_option('primary_font', 'Roboto Slab') }}', sans-serif;
            }
        </style>

        @php
            Theme::asset()->container('footer')->remove('simple-slider-js');
        @endphp

        {!! Theme::header() !!}

        <!--HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries-->
        <!--WARNING: Respond.js doesn't work if you view the page via file://-->
        <!--[if lt IE 9]><script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script><![endif]-->
    </head>
    <!--[if IE 7]><body class="ie7 lt-ie8 lt-ie9 lt-ie10"><![endif]-->
    <!--[if IE 8]><body class="ie8 lt-ie9 lt-ie10"><![endif]-->
    <!--[if IE 9]><body class="ie9 lt-ie10"><![endif]-->
    <body @if (BaseHelper::siteLanguageDirection() == 'rtl') dir="rtl" @endif>
    {!! apply_filters(THEME_FRONT_BODY, null) !!}
    <header id="header" data-sticky="false" data-sticky-checkpoint="200" data-responsive="991" class="page-header page-header--light">
        <div class="container d-flex justify-content-between align-items-sm-center">
            <!-- LOGO-->
            <div class="page-header__left">
                <a href="{{ route('public.single') }}" class="page-logo">
                    @if (theme_option('logo'))
                        <div>
                            <img src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" alt="{{ theme_option('site_title') }}" height="48">
                            <span class="text-uppercase text-light">Văn hóa phật giáo Việt Nam</span>
                        </div>
                    @endif
                </a>
            </div>
            <div class="page-header__center">
                <!-- MOBILE MENU-->
                <div class="navigation-toggle navigation-toggle--dark" style="display: none"><span></span></div>
                <div class="float-start">
                    <div class="search-btn c-search-toggler"><i class="fa fa-search close-search"></i> <span class="search-placeholder close-search d-none d-xl-inline-block">{{ __('Search') }}</span></div>
                    <nav class="navigation navigation--light navigation--fade navigation--fadeLeft">
                        {!!
                            Menu::renderMenuLocation('main-menu', [
                                'options' => ['class' => 'menu sub-menu--slideLeft'],
                                'view'    => 'main-menu',
                            ])
                        !!}

                        @if (is_plugin_active('member'))
                            <ul class="menu sub-menu--slideLeft d-block d-lg-none">
                                @if (auth('member')->check())
                                    <li class="menu-item"><a href="{{ route('public.member.dashboard') }}" rel="nofollow"><img src="{{ auth('member')->user()->avatar_url }}" class="img-circle" width="30" alt="{{ auth('member')->user()->name }}" loading="lazy"> &nbsp;<span>{{ auth('member')->user()->name }}</span></a></li>
                                    <li class="menu-item"><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" rel="nofollow"><i class="fa fa-sign-out"></i> {{ __('Logout') }}</a></li>
                                @else
                                    <li class="menu-item"><a href="{{ route('public.member.login') }}" rel="nofollow"><i class="fa fa-sign-in"></i> {{ __('Login') }}</a></li>
                                @endif
                            </ul>
                        @endif

                        <li class="language-wrapper d-block d-lg-none">
                            {!! apply_filters('language_switcher') !!}
                        </li>
                    </nav>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="page-header__right d-none d-lg-block">
                @if (is_plugin_active('member'))
                    @if (auth('member')->check())
                        <div>
                            <button class="btn dropdown-toggle" type="button" id="user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ auth('member')->user()->avatar_url }}" class="img-circle" width="32" alt="{{ auth('member')->user()->name }}" loading="lazy">
                            </button>
                            <ul class="dropdown-menu px-3 py-3" aria-labelledby="user-menu">
                                <li class="mb-2"><a href="{{ route('public.member.dashboard') }}" rel="nofollow"><span>{{ __('Trang cá nhân') }}</span> &nbsp;</a></li>
                                <li>
                                    <form id="logout-form" action="{{ route('public.member.logout') }}" method="POST">
                                        @csrf
                                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn-logout">Đăng xuất</a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('public.member.login') }}" rel="nofollow"><button type="button" class="btn btn-secondary btn-login mb-0">{{ __('Đăng nhập') }}</button></a>
                    @endif
                @endif
            </div>
        </div>
        @if (is_plugin_active('blog'))
            <div class="super-search hide" data-search-url="{{ route('public.ajax.search') }}">
                <form class="quick-search" action="{{ route('public.search') }}">
                    <input type="text" name="q" placeholder="{{ __('Type to search...') }}" class="form-control search-input" autocomplete="off">
                    <span class="close-search">&times;</span>
                </form>
                <div class="search-result"></div>
            </div>
        @endif
    </header>
    <div id="page-wrap">
