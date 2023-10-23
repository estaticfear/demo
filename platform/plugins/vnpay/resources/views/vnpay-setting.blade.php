@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    {!! Form::open(['route' => ['settings.vnpay-setting']]) !!}
    <div class="max-width-1200">
        <div class="flexbox-annotated-section">

            <div class="flexbox-annotated-section-annotation">
                <div class="annotated-section-title pd-all-20">
                    <h2>{{ trans('plugins/vnpay::setting.settings.title') }}</h2>
                </div>
                <div class="annotated-section-description pd-all-20 p-none-t">
                    <p class="color-note">{{ trans('plugins/vnpay::setting.settings.description') }}</p>
                </div>
            </div>

            <div class="flexbox-annotated-section-content">
                <div class="wrapper-content pd-all-20">
                    <div class="form-group mb-3">
                        <label class="text-title-field"
                               for="vnpay_is_enabled">{{ trans('plugins/vnpay::setting.settings.vnpay_is_enabled') }}
                        </label>
                        <label class="me-2">
                            <input type="radio" name="vnpay_is_enabled" class="setting-selection-option" data-target="#vnpay-is-enabled"
                                   value="1"
                                   @if (setting('vnpay_is_enabled')) checked @endif>{{ trans('core/setting::setting.general.yes') }}
                        </label>
                        <label>
                            <input type="radio" name="vnpay_is_enabled" class="setting-selection-option" data-target="#vnpay-is-enabled"
                                   value="0"
                                   @if (!setting('vnpay_is_enabled')) checked @endif>{{ trans('core/setting::setting.general.no') }}
                        </label>
                    </div>
                    <div class="form-group mb-3">
                        <input type="hidden" name="social_login_enable" value="0">
                        <label>
                            <input type="checkbox" class="hrv-checkbox" value="1"
                                @if (setting('vnpay_enable_sanbox')) checked @endif name="vnpay_enable_sanbox"
                                id="vnpay_enable_sanbox">
                            {{ trans('plugins/vnpay::setting.settings.vnpay_enable_sanbox') }}?
                        </label>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-title-field"
                            for="">{{ trans('plugins/vnpay::setting.settings.vnpay_terminal_id') }}</label>
                        <input data-counter="120" type="text" class="next-input" name="vnpay_terminal_id"
                            id="vnpay_terminal_id" value="{{ setting('vnpay_terminal_id') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-title-field"
                            for="">{{ trans('plugins/vnpay::setting.settings.vnpay_hash_secret') }}</label>
                        <input data-counter="120" type="text" class="next-input" name="vnpay_hash_secret"
                            id="vnpay_hash_secret" value="{{ setting('vnpay_hash_secret') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="flexbox-annotated-section" style="border: none">
            <div class="flexbox-annotated-section-annotation">
                &nbsp;
            </div>
            <div class="flexbox-annotated-section-content">
                <button class="btn btn-info" type="submit">{{ trans('core/setting::setting.save_settings') }}</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
