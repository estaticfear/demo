<?php

return [
    // List supported modules or plugins
    'supported' => [
        'Cmat\Gallery\Models\Gallery',
        'Cmat\Page\Models\Page',
        'Cmat\Blog\Models\Post',
    ],
    'use_language_v2' => env('GALLERY_USE_LANGUAGE_VERSION_2', false),
];
