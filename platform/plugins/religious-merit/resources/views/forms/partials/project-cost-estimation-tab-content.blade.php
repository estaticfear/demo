@php
    $repeaterValue = $model->cost_estimations;

    $fields = [
        [
            'type' => 'text',
            'label' => __('Hạng mục'),
            'label_attr' => ['class' => 'control-label required'],
            'attributes' => [
                'name' => 'name',
                'value' => null,
                'options' => [
                    'class' => 'form-control required',
                    'data-counter' => 255,
                ],
            ],
        ],
        [
            'type' => 'number',
            'label' => __('Số lượng'),
            'label_attr' => ['class' => 'control-label required'],
            'attr' => [
                'class' => 'form-control input-mask-number',
            ],
            'attributes' => [
                'name' => 'qty',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    // 'data-counter' => 10,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => __('Đơn vị tính'),
            'label_attr' => ['class' => 'control-label required'],
            'attr' => [
                'class' => 'form-control',
            ],
            'attributes' => [
                'name' => 'unit',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ],
        [
            'type' => 'number',
            'label' => __('Giá (trên 1 đơn vị)'),
            'label_attr' => ['class' => 'control-label required'],
            'attr' => [
                'class' => 'form-control input-mask-number',
            ],
            'attributes' => [
                'name' => 'cost_per_unit',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    // 'data-counter' => 17,
                ],
            ],
        ],
        [
            'type' => 'number',
            'label' => __('Tổng tiền đã chi'),
            'label_attr' => ['class' => 'control-label required'],
            'attributes' => [
                'name' => 'total_spent',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    // 'data-counter' => 20,
                ],
            ],
        ],
        [
            'field_name' => 'status',
            'type' => 'onOff',
            'label' => __('Hoàn thành'),
            'label_attr' => ['class' => 'control-label'],
            'default_value' => false,
            'attributes' => [
                'name' => 'status',
                'value' => null,
            ],
        ],
    ];
@endphp


<div class="tab-pane" id="tab_cost_estimation">
    <div class="form-group mb-3" style="min-height: 400px;">
        {!! Form::projectCostEstimationRepeater('cost_estimations', $repeaterValue, $fields) !!}
    </div>
</div>
