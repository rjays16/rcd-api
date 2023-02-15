<?php

return [
    'default' => 'local',

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => public_path('storage'),
    ],
        'public' => [
            'driver' => 'local',
            'root' => public_path('storage/images'),
            'url' => config('settings.APP_URL').'/storage',
            'visibility' => 'public'
        ]
    ]
];
