@php
    $limit = $merits->perPage();
    $page = $merits->currentPage();
@endphp
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th class="text-center">STT</th>
                <th>Người Đóng Góp</th>
                <th>Thời gian</th>
                <th>Loại Đóng Góp</th>
                <th class="text-end">Giá trị</th>
            </tr>
        </thead>
        <tbody class="table-light">
            @if ($merits->count())
                @foreach ($merits as $i => $merit)
                    <tr>
                        <td class="text-center">{{ $i + 1 + $limit * ($page - 1) }}</td>
                        <td>{{ !$merit->is_hidden ? $merit->name : 'Nhà hảo tâm ẩn danh' }}</td>
                        <td>{{ $merit->created_at->format('h:m d/m/Y') }}</td>
                        <td>
                            @if ($merit->type == \Cmat\ReligiousMerit\Enums\ReligiousTypeEnum::MONEY)
                                @if ($merit->payment_gate == \Cmat\ReligiousMerit\Enums\PaymentGateTypeEnum::CASH) Tiền mặt
                                @elseif ($merit->payment_gate == \Cmat\ReligiousMerit\Enums\PaymentGateTypeEnum::TRANSFER) Chuyển khoản
                                @else Vnpay
                                @endif
                            @elseif ($merit->type == \Cmat\ReligiousMerit\Enums\ReligiousTypeEnum::EFFORT) Công sức
                            @else Hiện vật
                            @endif
                        <td class="text-end">{{ currency_format($merit->amount) }}</td>
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
<div class="page-pagination mt-5 d-flex justify-content-center ajax">
    {!! $merits->withQueryString()->links() !!}
</div>
