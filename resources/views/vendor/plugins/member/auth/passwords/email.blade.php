@extends('plugins/member::layouts.skeleton')
@section('content')
    <style>
        main {
            background-color: #eeead8;
            font-family: 'Roboto Slab';
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
    </style>
    <div class="container section-login">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-5 content-left">
                <div class="card login-form">
                    <div class="card-body">
                        <a href="{{ route('public.index') }}">
                            <div class="d-flex justify-content-center">
                                @if (theme_option('logo'))
                                    <div class="mb-3">
                                        <img src="{{ RvMedia::getImageUrl(theme_option('logo')) }}"
                                            alt="{{ theme_option('site_title') }}">
                                        <div class="mt-2 text-uppercase text-dark font-weight-bold">Văn hóa phật giáo Việt
                                            Nam</div>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <h1 class="text-center title-login">{{ trans('plugins/member::member.forgot_password') }}</h1>
                        <br>
                        <form method="POST" action="{{ route('public.member.password.email') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <input id="email" type="email"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                    value="{{ old('email') }}" required
                                    placeholder="{{ trans('plugins/member::dashboard.email') }}">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn button-submit">
                                    {{ trans('plugins/member::dashboard.reset-password-cta') }}
                                  </button>
                                <div class="text-center">
                                    <a href="{{ route('public.member.login') }}"
                                        class="btn btn-link">{{ trans('plugins/member::dashboard.back-to-login') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-7 content-right">
                <div class="img-7-5 content-right-image">
                    <img src="/themes/buddhism/images/bg-login.jpg" />
                    <div class="content-right-text">
                        <div>
                            <div class="section-text d-flex justify-content-center ">
                                <h4 class="title-content">Bạn chưa có tài khoản? Tham gia ngay với chúng tôi</h4>
                            </div>
                            <div class="section-button justify-content-center d-flex">
                                <a href="{{ route('public.member.register') }}">
                                    <button class="btn btn-primary">Đăng ký</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('vendor/core/core/js-validation/js/js-validation.js') }}"></script>
    {!! JsValidator::formRequest(\Cmat\Member\Http\Requests\LoginRequest::class) !!}
@endpush
