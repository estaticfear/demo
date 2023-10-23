{!! Form::open(['url' => $url]) !!}

    <div class="form-group">
        <label for="shipment-status" class="control-label">{{ trans('plugins/ecommerce::shipping.status') }}</label>
        {!! Form::customSelect('status', \Cmat\Ecommerce\Enums\ShippingStatusEnum::labels(), $shipment->status) !!}
    </div>

{!! Form::close() !!}
