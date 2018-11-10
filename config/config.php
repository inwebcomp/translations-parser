<?php

return [
    'locales' => [
        'ru',
        'en'
    ],

    'directories' => [
        base_path('app'),
        resource_path('views'),
        resource_path('js')
    ],

    'excluded' => [
        base_path('app/Nova')
    ],

    'lang_files_directory' => resource_path('lang')
];
