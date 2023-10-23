@extends(BaseHelper::getAdminMasterLayoutTemplate())
<?php
    $banks = get_vietqr_banks();
    // echo '<pre>';print_r($banks);die;
?>
@section('content')
    {!! Form::open(['route' => ['settings.religious-merit-update']]) !!}
    <div class="max-width-1200">
        <div class="flexbox-annotated-section">
            <div class="flexbox-annotated-section-annotation">
                <div class="annotated-section-title pd-all-20">
                    <h2>{{ trans('plugins/religious-merit::settings.title') }}</h2>
                </div>
                <div class="annotated-section-description pd-all-20 p-none-t">
                    <p class="color-note">{{ trans('plugins/religious-merit::settings.description') }}</p>
                </div>
            </div>

            <div class="flexbox-annotated-section-content">
                <div class="wrapper-content pd-all-20">
                    {{-- <div class="form-group mb-3">
                        <label class="text-title-field"
                               for="bank_transfer_is_enabled">{{ trans('plugins/religious-merit::settings.bank_transfer_config.is_enabled') }}
                        </label>
                        <label class="me-2">
                            <input type="radio" name="bank_transfer_is_enabled" class="setting-selection-option" data-target="#bank-transfer-is-enabled"
                                   value="1"
                                   @if (setting('bank_transfer_is_enabled')) checked @endif>{{ trans('core/setting::setting.general.yes') }}
                        </label>
                        <label>
                            <input type="radio" name="bank_transfer_is_enabled" class="setting-selection-option" data-target="#bank-transfer-is-enabled"
                                   value="0"
                                   @if (!setting('bank_transfer_is_enabled')) checked @endif>{{ trans('core/setting::setting.general.no') }}
                        </label>
                    </div> --}}

                    {{-- <div class="form-group mb-3">
                        <label class="text-title-field"
                            for="merit_slug">{{ __('Slug dự án') }}</label>
                        <input data-counter="30" type="text" class="next-input" name="merit_slug" placeholder="du-an"
                            id="merit_slug" value="{{ setting('merit_slug') }}">
                    </div> --}}

                    <div class="form-group mb-3">
                        <label class="text-title-field"
                               for="bank_transfer_bank_name">{{ trans('plugins/religious-merit::settings.bank_transfer_config.bank_name') }}
                        </label>
                        <div class="ui-select-wrapper">
                            <select name="bank_transfer_bank_name" class="ui-select form-control select-search-full" id="bank_transfer_bank_name">
                                <option value="">Danh sách ngân hàng</option>
                                @foreach($banks as $bank)
                                    <option data-name={{ $bank->name }} value="{{ $bank->bin }}" @if (setting('bank_transfer_bank_name', '') === $bank->bin) selected @endif>{{ $bank->name }}</option>
                                @endforeach
                            </select>
                            <svg class="svg-next-icon svg-next-icon-size-16">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
                            </svg>
                        </div>
                    </div>

                    <div class="form-group">
                        <input hidden data-counter="120" type="text" class="next-input" name="bank_transfer_bank_name_text"
                            id="bank_transfer_bank_name_text" value="{{ setting('bank_transfer_bank_name_text') }}">
                    </div>

                    {{-- <div class="form-group mb-3">
                        <label class="text-title-field"
                            for="">{{ trans('plugins/religious-merit::settings.bank_transfer_config.bank_name') }}</label>
                        <input data-counter="120" type="text" class="next-input" name="bank_transfer_bank_name"
                            id="bank_transfer_bank_name" value="{{ setting('bank_transfer_bank_name') }}">
                    </div> --}}

                    <div class="form-group mb-3">
                        <label class="text-title-field"
                            for="">{{ trans('plugins/religious-merit::settings.bank_transfer_config.bank_account_number') }}</label>
                        <input data-counter="120" type="text" class="next-input" name="bank_transfer_bank_account_number"
                            id="bank_transfer_bank_account_number" value="{{ setting('bank_transfer_bank_account_number') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-title-field"
                            for="">{{ trans('plugins/religious-merit::settings.bank_transfer_config.bank_account_name') }}</label>
                        <input data-counter="120" type="text" class="next-input" name="bank_transfer_bank_account_name"
                            id="bank_transfer_bank_account_name" value="{{ setting('bank_transfer_bank_account_name') }}">
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

<script>
    window.addEventListener("load", (event) => {
        var bankNameElement = $('#bank_transfer_bank_name');
        bankNameElement.change(() => {
            var selectedName = $('#bank_transfer_bank_name').find(":selected").text();
            $('#bank_transfer_bank_name_text').val(selectedName);
        })

        var selectedName = $('#bank_transfer_bank_name').find(":selected").text();
        $('#bank_transfer_bank_name_text').val(selectedName);
    });
</script>
