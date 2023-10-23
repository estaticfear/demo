{!! Theme::partial('header') !!}
@php
$phone = '043 941 6599';
$fax = '043 941 6599';
$email = 'vanhoapgvn@gmail.com';
$address1 = 'Phòng 223, 73 Quán Sứ, Quận Hoàn Kiếm, TP. Hà Nội.';
$address2 = 'Phòng 8, 294 Nam Kỳ Khởi Nghĩa, Quận 3, TP. HCM';
$seeMap = '1';
@endphp
<section class="contact-page__googlemap">
    {!! do_shortcode('[google-map]73 Quán Sứ, Hoàn Kiếm, Hà Nội[/google-map]') !!}
    <!-- {!! do_shortcode('[google-map]294 Nam Kỳ Khởi Nghĩa, Quận 3, Hồ Chí Minh[/google-map]') !!} -->
</section>
<section class="contact-page__information page-section">
    {!! Form::open(['route' => 'public.send.contact', 'method' => 'POST', 'class' => 'contact-form container']) !!}
    <div class="row mb-3">
        <div class="contact-form-group contact-form__message body-2__regular d-none d-lg-block">
            <div class="contact-message contact-success-message" style="display: none"></div>
            <div class="contact-message contact-error-message" style="display: none"></div>
        </div>
        <div class="col-lg-6">
            <h1 class="contact-page__title h3">Thông tin liên hệ</h1>
            <h2 class="contact-page__subtitle body-1__regular">Nếu cần bất kỳ thông tin xin vui lòng liên hệ với chúng tôi!</h2>
            <div class="contact-page__content">
                <div class="phone sub-title-1__regular">
                    <span>Hotline: <a href="tel:<?php echo (implode(explode(' ', $phone))) ?>">{{ $phone }}</a></span>
                    <span>Fax: {{ $fax }}</span>
                </div>
                <div class="email sub-title-1__regular">
                    <span>Email: <a href="mailto:{{ $email }}">{{ $email }}</a></span>
                </div>
                <div class="address">
                    <div class="address-list sub-title-1__regular">
                        <span>VP 1: <?php echo ($address1) ?></span>
                        <span>VP 2: <?php echo ($address2) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 contact-form-fields">
            <!-- {!! do_shortcode('[contact-form][/contact-form]') !!} -->
            <!-- START FORM FIELDS -->
            {!! apply_filters('pre_contact_form', null) !!}
            <!-- <input type="text" class="contact-form-input" name="address" value=" " id="contact_address" style="display:none;">
            <input type="email" class="contact-form-input" name="email" value="example@example.example" id="contact_email" style="display:none;"> -->

            <div class="contact-form-group contact-form__message body-2__regular d-block d-lg-none">
                <div class="contact-message contact-success-message" style="display: none"></div>
                <div class="contact-message contact-error-message" style="display: none"></div>
            </div>

            <div class="contact-form-fields__input">
                <label for="contact_name" class="body-2__regular required">{{ __('Họ và tên') }}</label>
                <input type="text" class="body-1__regular input_field" name="name" value="{{ old('name') }}" id="contact_name" placeholder="{{ __('Nhập họ và tên') }}">
            </div>

            <div class="contact-form-fields__input">
                <label for="contact_phone" class="body-2__regular required">{{ __('Số điện thoại') }}</label>
                <input type="text" class="body-1__regular input_field" name="phone" value="{{ old('phone') }}" id="contact_phone" placeholder="{{ __('Nhập số điện thoại') }}">
            </div>

            <div class="contact-form-fields__input">
                <label for="contact_subject" class="body-2__regular required">{{ __('Tiêu đề') }}</label>
                <input type="text" class="body-1__regular input_field" name="subject" value="{{ old('subject') }}" id="contact_subject" placeholder="{{ __('Nhập tiêu đề') }}">
            </div>

            <div class="contact-form-fields__input">
                <label for="contact_content" class="body-2__regular">{{ __('Nội dung') }}</label>
                <textarea name="content" id="contact_content" class="body-1__regular input_field" rows="5" placeholder="{{ __('Nhập nội dung') }}">{{ old('content') }}</textarea>
            </div>

            @if (is_plugin_active('captcha'))
            @if (setting('enable_captcha'))
            <div class="contact-form-row">
                <div class="contact-column-12">
                    <div class="contact-form-group">
                        {!! Captcha::display() !!}
                    </div>
                </div>
            </div>
            @endif

            @if (setting('enable_math_captcha_for_contact_form', 0))
            <div class="contact-form-group">
                <label for="math-group" class="contact-label required">{{ app('math-captcha')->label() }}</label>
                {!! app('math-captcha')->input(['class' => 'contact-form-input', 'id' => 'math-group']) !!}
            </div>
            @endif
            @endif

            {!! apply_filters('after_contact_form', null) !!}
            <!-- STOP FORM FIELDS -->
        </div>
    </div>
    <div class="contact-form-fields__submit">
        <button type="submit" class="submit-button btn btn-primary body-2__medium">{{ __('Gửi') }}</button>
    </div>
    {!! Form::close() !!}
</section>
{!! Theme::partial('footer') !!}
