<?php

return [
    'schema_supported' => [
        'Cmat\Page\Models\Page',
        'Cmat\Blog\Models\Post',
    ],
    'use_language_v2' => env('FAQ_USE_LANGUAGE_VERSION_2', false),
];
