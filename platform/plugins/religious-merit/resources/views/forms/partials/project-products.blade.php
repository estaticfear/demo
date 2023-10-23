
@if ($project_id)
<div class="max-width-1200" id="main-order">
    <select-physical-products :currency="'{{ get_application_currency()->symbol }}'"
        :project_id="'<?= $project_id ?>'"
        :product_type="'physical'"
        :zip_code_enabled="{{ (int)EcommerceHelper::isZipCodeEnabled() }}"
        :use_location_data="{{ (int)EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation() }}"
        :is_tax_enabled={{ (int)EcommerceHelper::isTaxEnabled() }}
        :sub_amount_label="'{{ format_price(0) }}'"
        :tax_amount_label="'{{ format_price(0) }}'"
        :promotion_amount_label="'{{ format_price(0) }}'"
        :discount_amount_label="'{{ format_price(0) }}'"
        :shipping_amount_label="'{{ format_price(0) }}'"
        :total_amount_label="'{{ format_price(0) }}'"></select-physical-products>
    <hr style="border-top: 1px solid #000" />
    <select-digital-products :currency="'{{ get_application_currency()->symbol }}'"
        :project_id="'<?= $project_id ?>'"
        :product_type="'digital'"
        :zip_code_enabled="{{ (int)EcommerceHelper::isZipCodeEnabled() }}"
        :use_location_data="{{ (int)EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation() }}"
        :is_tax_enabled={{ (int)EcommerceHelper::isTaxEnabled() }}
        :sub_amount_label="'{{ format_price(0) }}'"
        :tax_amount_label="'{{ format_price(0) }}'"
        :promotion_amount_label="'{{ format_price(0) }}'"
        :discount_amount_label="'{{ format_price(0) }}'"
        :shipping_amount_label="'{{ format_price(0) }}'"
        :total_amount_label="'{{ format_price(0) }}'"></select-digital-products>
</div>
@endif
