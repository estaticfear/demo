@php
    Assets::addScriptsDirectly('vendor/core/core/base/js/repeater-field.js')->usingVueJS();

    $wrapper = '';
    $group = '';
    foreach ($fields as $key => $field) {
        $fieldName = $field['attributes']['name'];
        $item = Form::hidden($name . '[__key__][' . $key . '][key]', $field['attributes']['name']);
        $field['attributes']['name'] = $name . '[__key__][' . $key . '][value]';
        $field['attributes']['options']['id'] = 'repeater_field_' . md5($field['attributes']['name']) . '__key__';
        Arr::set($field, 'label_attr.for', $field['attributes']['options']['id']);
        $item .= Form::customLabel(Arr::get($field, 'attr.name'), $field['label'], Arr::get($field, 'label_attr')) . call_user_func_array([Form::class, $field['type']], array_values($field['attributes']));

        switch ($fieldName) {
            case 'name':
                $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                break;
            case 'qty':
                $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                break;
            case 'unit':
                $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                break;
            case 'cost_per_unit':
                $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                break;
            case 'total_spent':
                $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                break;
            case 'status':
                $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                break;
            default:
                break;
        }
    }

    $defaultFields = ['<div style="overflow-x: scroll"><table class="table table-borderless" style="min-width: 900px"><tr class="repeater-item-group mb-3 ">' . $group . '</tr></table></div>'];
    // echo $value;die;
    if ($value == '[]') $value = [];
    // $values = (array) json_decode($value ?: '[]', true);
    $values = array_values($value ?: []);

    $added = [];

    if (count($values) > 0) {
        for ($i = 0; $i < count($values); $i++) {
            $group = '';
            foreach ($fields as $key => $field) {
                $fieldName = $field['attributes']['name'];
                $item = Form::hidden($name . '[' . $i . '][' . $key . '][key]', $field['attributes']['name']);
                $field['attributes']['name'] = $name . '[' . $i . '][' . $key . '][value]';
                $field['attributes']['value'] = Arr::get($values, $i . '.' . $key . '.value');
                $field['attributes']['options']['id'] = 'repeater_field_' . md5($field['attributes']['name']);
                Arr::set($field, 'label_attr.for', $field['attributes']['options']['id']);
                $item .= Form::customLabel(Arr::get($field, 'attr.name'), $field['label'], Arr::get($field, 'label_attr')) . call_user_func_array([Form::class, $field['type']], array_values($field['attributes']));

                switch ($fieldName) {
                    case 'name':
                        $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                        break;
                    case 'qty':
                        $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                        break;
                    case 'unit':
                        $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                        break;
                    case 'cost_per_unit':
                        $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                        break;
                    case 'cost_per_unit':
                        $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                        break;
                    case 'total_spent':
                        $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                        break;
                    case 'status':
                        $group .= '<td class="mx-1 mb-2">' . $item . '</td>';
                        break;
                    default:
                        break;
                }
            }

            $added[] = '<div style="overflow-x: scroll"><table class="table table-borderless" style="min-width: 900px"><tr class="repeater-item-group mb-3 ">' . $group . '</tr></table></div>';
        }
    }
@endphp
@section('head')
<style>
    #tab_cost_estimation .repeater-group .remove-item-button {
        top: -12px;
        right: -12px;
        background-color: red;
        color: white;
    }
    #tab_cost_estimation small.charcounter {
        position: absolute;
        right: 25px;
        top: -15px;
    }
</style>
@endsection
<input type="hidden" name="{{ $name }}" value="[]">
<repeater-component :fields="{{ json_encode($defaultFields) }}" :added="{{ json_encode($added) }}"></repeater-component>
