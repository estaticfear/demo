@php
    $disable_class = $item->status == \Cmat\Vnpay\Enums\VnpayTransactionStatusEnum::SUCCESS ? ' disabled' : '';
@endphp
<div class="table-actions">
    <a href="#" class="btn btn-icon btn-sm btn-danger re-sync-vnpay{{$disable_class}}"
       data-bs-toggle="tooltip" data-section="{{ route('vnpay.resync', $item->id) }}"
       role="button"
       data-bs-original-title="{{ trans('plugins/vnpay::vnpay.tables.resync') }}">
        <i class="fa fa-sync"></i>
    </a>
    @if (Auth::user()->hasPermission($edit))
        <a href="{{ route($edit, $item->id) }}" class="btn btn-icon btn-sm btn-primary" data-bs-toggle="tooltip"
           data-bs-original-title="{{ trans('core/base::tables.edit') }}"><i class="fa fa-edit"></i></a>
    @endif
</div>
