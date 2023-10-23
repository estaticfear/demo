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
          <h2 class="text-center title-login">Đăng nhập</h2>
          <div class="d-flex justify-content-center login-with-social">
            <img src="/themes/buddhism/images/fb.jpg" alt="Facebook" />
            <img src="/themes/buddhism/images/gg.jpg" alt="Google" />
          </div>
          <h3 class="sub-text">Hoặc đăng nhập bằng địa chỉ email</h3>
          <form method="POST" action="{{ route('public.member.login') }}">
            @csrf
            <div class="form-group mb-3">
              <label for="email">Email</label>
              <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Nhập email" name="email" value="{{ old('email') }}" autofocus>
              @if ($errors->has('email'))
              <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
              @endif
            </div>
            <div class="form-group mb-3">
              <label for="password">Mật khẩu</label>
              <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Nhập mật khẩu" name="password">
              @if ($errors->has('password'))
              <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
              @endif
            </div>
            <div class="text-end forget-pass-section">
              <a class="forget-pass" href="{{ route('public.member.password.request') }}">
                Quên mật khẩu ?
              </a>
            </div>
            <div class="form-group mb-0">
              <button type="submit" class="btn button-submit">
                Đăng nhập
              </button>
            </div>
            <div class="text-center">
              {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Cmat\Member\Models\Member::class) !!}
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
              <a href="{{ route('public.member.register')}}">
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
<script type="text/javascript" src="{{ asset('vendor/core/core/js-validation/js/js-validation.js')}}"></script>
{!! JsValidator::formRequest(\Cmat\Member\Http\Requests\LoginRequest::class); !!}
@endpush