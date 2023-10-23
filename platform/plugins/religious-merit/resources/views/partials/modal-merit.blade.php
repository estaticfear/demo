<div class="modal fade modal-lg merit-form {{ $name }} fs-6" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header px-4 bg-{{ $type }}">
                <h4 class="modal-title text-primary"><i class="til_img"></i><strong>{{ $title }}</strong></h4>
                <button type="button" class="btn-close merit-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            {!! Form::open(['route' => 'public.religious-merit.merit', 'class' => 'modal-body p-4', 'id' => 'merit-form']) !!}
            <div class="row g-4 mb-4 align-items-center">
                @if (setting('vnpay_is_enabled'))
                    <div class="col-12 col-sm-6">
                        <div class="merit-tab active">
                            <a href="#vnpay"><img src="/vendor/core/plugins/religious-merit/images/vnpay.png"
                                    alt="vnpay"></a>
                        </div>
                    </div>
                @endif
                <div class="col-12 col-sm-6">
                    <div class="merit-tab @if (!setting('vnpay_is_enabled')) active @endif">
                        <a href="#transfer" class="d-flex gap-2">
                            <img src="/vendor/core/plugins/religious-merit/images/card.png" alt="card">
                            <span class="fw-bold text-primary text-center">{!! trans('plugins/religious-merit::religious-merit.bank_transfer') !!}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div id="vnpay"
                class="merit-tab-content text-center mb-5 @if (!setting('vnpay_is_enabled')) d-none @endif">
                <div class="mb-4">{!! trans('plugins/religious-merit::religious-merit.payment_support') !!}</div>
                <div class="d-flex flex-wrap gap-3 align-items-center justify-content-center mb-3">
                    <img src="/vendor/core/plugins/religious-merit/images/agri.png" alt="agri">
                    <img src="/vendor/core/plugins/religious-merit/images/bidv.png" alt="bidv">
                    <img src="/vendor/core/plugins/religious-merit/images/vietcom.png" alt="vietcom">
                    <img src="/vendor/core/plugins/religious-merit/images/vietin.png" alt="vietin">
                    <img src="/vendor/core/plugins/religious-merit/images/techcom.png" alt="techcom">
                    <img src="/vendor/core/plugins/religious-merit/images/tp.png" alt="tp">
                    <img src="/vendor/core/plugins/religious-merit/images/vib.png" alt="vib">
                    <img src="/vendor/core/plugins/religious-merit/images/vp.png" alt="vp">
                    <img src="/vendor/core/plugins/religious-merit/images/scb.png" alt="scb">
                    <img src="/vendor/core/plugins/religious-merit/images/mb.png" alt="mb">
                </div>
                <span>{{ trans('plugins/religious-merit::religious-merit.and_many_other_banks') }}</span>
            </div>
            <div id="transfer" class="merit-tab-content"></div>
            <div class="merit-form-input">
                <input type="hidden" class="form-control" id="payment_gate" name="payment_gate"
                    value="{{ setting('vnpay_is_enabled') ? 'vnpay' : 'transfer' }}">
                <input type="hidden" class="form-control" id="project-id" name="project_id" value="1">
                <div class="row gx-4 gy-3 mb-3">
                    <div class="col-12 col-sm-6 form-group">
                        <label for="name"
                            class="mb-1 required">{{ trans('plugins/religious-merit::religious-merit.merit_person_fullname') }}</label>
                        <input type="text" required class="form-control bg-white" id="name" name="name"
                            placeholder="{{ trans('plugins/religious-merit::religious-merit.fullname') }}">
                        <div class="feedback"></div>
                    </div>
                    <div class="col-12 col-sm-6 form-group">
                        <label for="phone_number"
                            class="mb-1">{{ trans('plugins/religious-merit::religious-merit.phone_number') }}</label>
                        <input type="tel" class="form-control bg-white" id="phone_number" name="phone"
                            placeholder="{{ trans('plugins/religious-merit::religious-merit.phone_number') }}">
                        <div class="feedback"></div>
                    </div>
                </div>
                <div class="row gx-4 gy-3 mb-4">
                    <div class="col-12 col-sm-6 form-group">
                        <label for="address"
                            class="mb-1">{{ trans('plugins/religious-merit::religious-merit.address') }}</label>
                        <input type="text" class="form-control bg-white" id="address" name="address"
                            placeholder="{{ trans('plugins/religious-merit::religious-merit.address') }}">
                        <div class="feedback"></div>
                    </div>
                    <div class="col-12 col-sm-6 form-group">
                        <label for="email" class="mb-1">Email</label>
                        <input type="email" class="form-control bg-white" id="email" name="email"
                            placeholder="Email">
                        <div class="feedback"></div>
                    </div>
                </div>
                <div class="border-top pt-4">
                    <div class="row gx-4 gy-3 amount align-items-start mb-4">
                        <div class="col-12 col-sm-6">
                            <span
                                class="h4">{{ trans('plugins/religious-merit::religious-merit.merit_amount') }}</span>
                        </div>
                        <div class="col-12 col-sm-6 form-group">
                            <input type="tel" class="form-control fw-bold fs-5" id="amount" name="amount"
                                placeholder="{{ trans('plugins/religious-merit::religious-merit.merit_amount') }}">
                            <div class="feedback"></div>
                        </div>
                    </div>
                    <div class="row gx-4 gy-3">
                        <div class="col-12 col-lg-6">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="isHidden" name="is_hidden">
                                <label class="form-check-label fw-normal"
                                    for="isHidden">{{ trans('plugins/religious-merit::religious-merit.merit_anonymous') }}</label>
                                <div class="feedback"></div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            @if (is_plugin_active('captcha'))
                                @if (setting('enable_captcha'))
                                    <div class="d-lg-flex justify-content-end">
                                        <div class="form-group">
                                            {!! Captcha::display() !!}
                                            <div class="feedback feedback-captcha"></div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="transfer-info">
                <div class="row g-4 align-items-center mb-4 text-center">
                    <div class="col-12 col-sm-6">
                        <input type="hidden" value="{{ get_vietqr_code() }}" class="qrcode-template">
                        <img src="" alt="qrcode" class="qrcode">
                    </div>
                    <div class="col-12 col-sm-6">
                        <h3 class="h4 my-3">{{ setting('bank_transfer_bank_name_text') }}</h3>
                        <!-- <img src="/vendor/core/plugins/religious-merit/images/techcombank.png" alt="techcombank"> -->
                        <div class="mb-2">{{ trans('plugins/religious-merit::religious-merit.account_name') }}:
                            <span class="text-uppercase">{{ setting('bank_transfer_bank_account_name') }}</span></div>
                        <div class="form-group d-flex justify-content-center mb-3">
                            <input type="text" id="transaction_account"
                                data-clipboard-target="#transaction_account"
                                class="copy-input text-center form-control w-75 text-primary fw-bold border-primary" readonly
                                value="{{ setting('bank_transfer_bank_account_number') }}">
                        </div>
                        <div class="form-group">
                            <div class="mb-2">
                                {{ trans('plugins/religious-merit::religious-merit.transfer_content') }}</div>
                            <div class="d-flex justify-content-center">
                                <input id="transaction_message" readonly type="text"
                                    data-clipboard-target="#transaction_message"
                                    class="copy-input text-center form-control w-75 text-primary fw-bold border-primary transfer-content"
                                    maxlength="50">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rules fw-normal"><span class="fw-bold">Chú ý: </span>Vui lòng nhập đúng nội dung chuyển
                    khoản để giao dịch được xác thực nhanh chóng. Tham khảo thêm hướng dẫn chuyển khoản <a
                        target="_blank" href="/huong-dan-cong-duc">tại đây</a>.</div>
            </div>
            {!! Form::close() !!}

            <div class="modal-footer pt-2 pb-4 px-4">
                <div class="w-100 upload-image-message fw-bold"></div>
                <div class="w-100 form-upload-image" style="display: none">
                    <p>
                        <strong>Quý Phật tử vui lòng tải ảnh chụp màn hình chuyển khoản thành công để việc xác thực được
                            nhanh chóng.</strong>
                    </p>
                    {!! Form::open([
                        'route' => 'public.religious-merit.upload-transaction-image',
                        'id' => 'upload-transaction-form',
                    ]) !!}
                    <input type="file" accept=".jpg, .png, .jpeg" name="file" class="mb-3 input-upload-image">
                    <button class="btn btn-secondary w-100 btn-upload-image">Tải ảnh giao dịch</button>
                    {!! Form::close() !!}
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-complete-merit" style="display: none">Hoàn thành</button>
                <button type="submit" class="btn btn-primary w-100 btn-submit-merit">{{ __('Đóng góp') }}</button>
                <span class="w-100 text-center rules">Chúng tôi xác nhận là bạn đã đồng ý với <a target="_blank"
                        href="/dieu-khoan">điều khoản</a> của chúng tôi</span>
            </div>
        </div>
    </div>
</div>
@include('plugins/religious-merit::partials.modal-thank-message', ['class' => 'modal-thank-message-merit'])
