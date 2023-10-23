<?php

return [
    [
        'name' => 'Religious plugins',
        'flag' => 'religious.index',
    ],
    [
        'name' => 'Religious merit',
        'flag' => 'religious-merit.index',
    ],
    [
        'name' => 'Religious merit projects',
        'flag' => 'religious-merit-project.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'religious-merit-project.create',
        'parent_flag' => 'religious-merit-project.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'religious-merit-project.edit',
        'parent_flag' => 'religious-merit-project.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'religious-merit-project.destroy',
        'parent_flag' => 'religious-merit-project.index',
    ],

    // project category
    [
        'name' => 'Religious merit project categories',
        'flag' => 'religious-merit-project-category.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'religious-merit-project-category.create',
        'parent_flag' => 'religious-merit-project-category.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'religious-merit-project-category.edit',
        'parent_flag' => 'religious-merit-project-category.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'religious-merit-project-category.destroy',
        'parent_flag' => 'religious-merit-project-category.index',
    ],

    [
        'name' => 'Setting',
        'flag' => 'religious-merit.settings',
        'parent_flag' => 'religious-merit.index',
    ],
];
