<?php

return [
    [
        'name' => 'Our members',
        'flag' => 'our-member.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'our-member.create',
        'parent_flag' => 'our-member.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'our-member.edit',
        'parent_flag' => 'our-member.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'our-member.destroy',
        'parent_flag' => 'our-member.index',
    ],
];
