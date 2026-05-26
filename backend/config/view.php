<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Blade templates live in the frontend workspace so UI files stay separated
    | from Laravel's application code while keeping server-rendered pages.
    |
    */

    'paths' => [
        realpath(base_path('../frontend/resources/views')) ?: base_path('../frontend/resources/views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views')) ?: storage_path('framework/views')
    ),

];
