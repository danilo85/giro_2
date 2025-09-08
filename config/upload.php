<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for file uploads in the application.
    | You can specify allowed file types, maximum file sizes, and storage paths.
    |
    */

    'logos' => [
        'allowed_types' => ['image/jpeg', 'image/png', 'image/svg+xml'],
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'svg'],
        'max_size' => 2048, // 2MB in kilobytes
        'storage_path' => 'public/logos',
        'validation_rules' => [
            'required',
            'file',
            'mimes:jpg,jpeg,png,svg',
            'max:2048'
        ]
    ],

    'assinaturas' => [
        'allowed_types' => ['image/jpeg', 'image/png'],
        'allowed_extensions' => ['jpg', 'jpeg', 'png'],
        'max_size' => 1024, // 1MB in kilobytes
        'storage_path' => 'public/assinaturas',
        'validation_rules' => [
            'required',
            'file',
            'mimes:jpg,jpeg,png',
            'max:1024'
        ]
    ],

    'avatars' => [
        'allowed_types' => ['image/jpeg', 'image/png'],
        'allowed_extensions' => ['jpg', 'jpeg', 'png'],
        'max_size' => 1024, // 1MB in kilobytes
        'storage_path' => 'public/avatars',
        'validation_rules' => [
            'required',
            'file',
            'mimes:jpg,jpeg,png',
            'max:1024'
        ]
    ],

];