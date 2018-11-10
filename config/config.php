<?php

return [
    // Locales to save phrases
    'locales' => [
        'ru',
        'en'
    ],

    // Directories in which phrases are searched
    'directories' => [
        base_path('app'),
        resource_path('views'),
        resource_path('js')
    ],

    // Excluded directories or files
    'excluded' => [
        base_path('app/Nova')
    ],

    // Where is your folder with translations
    'lang_files_directory' => resource_path('lang')
];
