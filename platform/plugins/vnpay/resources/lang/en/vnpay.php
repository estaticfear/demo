<?php

return [
    'name' => 'Vnpays',
    'create' => 'New vnpay',
    'edit' => 'Edit vnpay',

    'statuses' => [
        'new' => 'New',
        'success' => 'Success',
        'fail' => 'Fail',
        'not-completed' => 'Not completed'
    ],

    'notices' => [
        'tran-info-updated' => 'Update transaction info from VNPAY Successfully',
        'vnpay-call-error' => 'Call VNPAY error with code '
    ],

    'tables' => [
        'resync' => 'Update transaction info from VNPAY'
    ],
];
