<?php

return [
    'layout' => env('ADMIN_LAYOUT_STYLE', 'sidebar'),
    'cache' => [
        'features' => base_path('bootstrap/cache/admin_features.php'),
        'layouts' => base_path('bootstrap/cache/admin_layouts.php'),
    ],
];