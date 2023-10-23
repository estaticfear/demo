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
                        <div class="d-flex justify-content-center img-8-3">
                            <img src="/themes/buddhism/images/logo.png" alt="logo" />
                        </div>
                    </a>
                    <h2 class="text-center title-login">Đăng ký tài khoản</h2>
                    <div class="d-flex justify-content-center login-with-social">
                        <img src="/themes/buddhism/images/fb.jpg" alt="Facebook" />
                        <img src="/themes/buddhism/images/gg.jpg" alt="Google" />
                    </div>
                    <h3 class="sub-text">Hoặc đăng ký bằng địa chỉ email</h3>
                    <form method="POST" action="{{ route('public.member.register') }}">
                        @csrf
                        <!-- <div class="form-group mb-3">
                            <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" required autofocus placeholder="{{ trans('plugins/member::dashboard.first_name') }}">
                            @if ($errors->has('first_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                            @endif
                        </div> -->
                        <!-- <div class="form-group mb-3">
                            <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required placeholder="{{ trans('plugins/member::dashboard.last_name') }}">
                            @if ($errors->has('last_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                            @endif
                        </div> -->
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Nhập email">
                            @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Mật khẩu</label>
                            <div id="show_hide_password">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Nhập mật khẩu">
                                <div class="input-group-addon">
                                    <i class="fa fa-eye-slash" aria-hidden="true" id="show-and-hide-pass"></i>
                                </div>
                            </div>
                            @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="text-start sub-text-register">
                            <a class="sub-text" href="{{ route('public.member.password.request') }}">
                                Email xác nhận sẽ được gửi tới hộp thư của bạn
                            </a>
                        </div>
                        <!-- <div class="form-group mb-3">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="{{ trans('plugins/member::dashboard.password-confirmation') }}">
                        </div> -->

                        @if (is_plugin_active('captcha') && setting('enable_captcha') && setting('member_enable_recaptcha_in_register_page', 0))
                        <div class="form-group mb-3">
                            {!! Captcha::display() !!}
                        </div>
                        @endif

                        <div class="form-group mb-0">
                            <button type="submit" class="btn button-submit">
                                Đăng ký
                            </button>
                        </div>
                        <div class="text-start sub-text-register pt-3">
                            <span>Bằng cách đăng ký, bạn đồng ý với </span>
                            <span class="rules">
                                <a class="sub-text" href="/dieu-khoan">
                                    điều khoản
                                </a>
                            </span>
                            <span>của chúng tôi</span>

                        </div>

                        <div class="text-center">
                            {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Cmat\Member\Models\Member::class) !!}
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7 content-right content-right-register">
            <div class="img-7-5 content-right-image">
                <img src="/themes/buddhism/images/bg-login.jpg" />
                <div class="content-right-text">
                    <div class="section-text d-flex justify-content-center ">
                        <h4 class="title-content">Bạn đã có tài khoản? Đăng nhập ngay để trải nghiệm</h4>
                    </div>
                    <div class="section-button justify-content-center d-flex">
                        <a href="{{ route('public.member.login')}}">
                            <button class="btn btn-primary">Đăng nhập</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/core/core/js-validation/js/js-validation.js')}}"></script>
{!! JsValidator::formRequest(\Cmat\Member\Http\Requests\RegisterRequest::class); !!}
@endpush