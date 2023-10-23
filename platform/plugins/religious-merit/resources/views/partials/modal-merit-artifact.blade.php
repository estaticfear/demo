<div class="modal fade modal-lg {{ $name }} fs-6" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header px-4 bg-{{ $type }}">
                <h4 class="modal-title text-primary"><i class="til_img"></i><strong>{{ $title }}</strong></h4>
                <button type="button" class="btn-close merit-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>

            {!! Form::open(['route' => 'public.religious-merit.merit', 'class' => 'modal-body p-4 merit-artifact-form']) !!}
            <div class="merit-artifact-form-input">
                <input type="hidden" class="form-control" id="project-id" name="project_id" value="1">
                <input type="hidden" class="form-control" name="type" value="artifact">
                <div class="row gx-4 gy-3 mb-3">
                    <div class="col-12 col-sm-6 form-group">
                        <label for="name"
                            class="mb-1 required">{{ trans('plugins/religious-merit::religious-merit.merit_person_fullname') }}</label>
                        <input type="text" class="form-control bg-white" id="name" name="name"
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
                <div class="border-top pt-3">
                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
                        <div class="fw-bold">Danh sách hiện vật</div>
                        <button class="btn btn-primary mb-0 d-flex gap-2 align-items-center btn-add-artifact" type="button">
                            <img src="/vendor/core/plugins/religious-merit/images/plus.png" alt="plus" width="20">
                            Thêm hiện vật
                        </button>
                    </div>
                    <div class="table-responsive project-tabs">
                        <table class="table table-form-artifact">
                            <thead>
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th>Ảnh</th>
                                    <th>Tên hiện vật</th>
                                    <th class="text-end">Số lượng</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="row gx-4 gy-3">
                        <div class="col-12 col-lg-6">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="isHiddenArtifact" name="is_hidden">
                                <label class="form-check-label fw-normal" for="isHiddenArtifact">{{ trans('plugins/religious-merit::religious-merit.merit_anonymous') }}</label>
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
                    <div class="merit-artifact-form-message fw-bold mb-0"></div>
                </div>
            </div>
            {!! Form::close() !!}

            <div class="modal-body p-4 merit-artifact-list">
                <div class="input-group mb-4 border rounded">
                    <span class="input-group-text bg-white border-white">
                        <img src="/vendor/core/plugins/religious-merit/images/search.png" alt="search">
                    </span>
                    <input type="text" class="form-control border-white input-search input-search-merit-artifacts bg-white" placeholder="Tên hiện vật">
                </div>
                <div class="table-responsive project-tabs project-table-modal">
                    <table class="table">
                        <thead class="sticky-top">
                            <tr>
                                <th class="text-center">Lựa chọn</th>
                                <th>Ảnh</th>
                                <th>Tên hiện vật</th>
                                <th class="text-end">Số lượng yêu cầu</th>
                                <th class="text-end">Số lượng đóng góp</th>
                            </tr>
                        </thead>
                        <tbody class="table-light">
                            @if (!empty($artifacts))
                                @foreach ($artifacts as $artifact)
                                    <tr class="align-middle artifact-row" data-product-name="{{ preg_replace('/\s+/', '', $artifact->product->name) }}">
                                        <td>
                                            <div class="form-check mb-0 d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" name="checkbox" @php if($artifact->qty - $artifact->total_merit_qty < 1) { echo 'disabled'; } @endphp>
                                            </div>
                                        </td>
                                        <td>
                                            <img src="{{ RvMedia::getImageUrl($artifact->product->image, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $artifact->product->name }}" loading="lazy" class="project-artifact-image">
                                        </td>
                                        <td>{{ $artifact->product->name }}</td>
                                        <td class="text-end">{{ $artifact->qty - $artifact->total_merit_qty }}</td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end">
                                                <input type="hidden" name="id" value="{{ $artifact->id }}">
                                                <input type="hidden" name="image" value="{{ RvMedia::getImageUrl($artifact->product->image, 'medium', false, RvMedia::getDefaultImage()) }}">
                                                <input type="hidden" name="name" value="{{ $artifact->product->name }}">
                                                <input type="hidden" name="quantityOrigin" value="{{ $artifact->qty - $artifact->total_merit_qty }}">
                                                <input type="text" class="form-control text-end" name="quantity" value="{{ $artifact->qty - $artifact->total_merit_qty }}" placeholder="0" style="width: 140px"
                                                    @php if($artifact->is_not_allow_merit_a_part || $artifact->qty - $artifact->total_merit_qty < 1) { echo 'disabled'; } @endphp >
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="5">Không có dữ liệu</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer p-4">
                <div class="merit-artifact-form w-100 text-center">
                    <button class="btn btn-primary w-100 btn-submit-merit-artifact-form">{{ $submit_text }}</button>
                    <span class="rules">Chúng tôi xác nhận là bạn đã đồng ý với
                        <a target="_blank" href="/dieu-khoan">điều khoản</a> của chúng tôi
                    </span>
                </div>
                <div class="merit-artifact-list w-100">
                    <button class="btn btn-primary w-100 mb-0 btn-submit-merit-artifact-list">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('plugins/religious-merit::partials.modal-thank-message', ['class' => 'modal-thank-message-merit-artifact'])
