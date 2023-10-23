<?php

return [
    [
        'name' => 'Vnpays',
        'flag' => 'vnpay.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'vnpay.create',
        'parent_flag' => 'vnpay.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'vnpay.edit',
        'parent_flag' => 'vnpay.index',
    ],
    [
        'name' => 'ReSync',
        'flag' => 'vnpay.resync',
        'parent_flag' => 'vnpay.index',
    ],
    [
        'name' => 'Setting',
        'flag' => 'vnpay.setting',
        'parent_flag' => 'vnpay.index',
    ],
];
