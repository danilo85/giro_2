<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for file uploads in the application.
    | You can adjust the maximum file size, allowed file types, and other
    | upload-related settings here.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Maximum File Size
    |--------------------------------------------------------------------------
    |
    | The maximum file size allowed for uploads in kilobytes.
    | Default: 20480 KB (20 MB)
    |
    */

    'max_file_size' => env('UPLOAD_MAX_FILE_SIZE', 20480), // 20MB in KB

    /*
    |--------------------------------------------------------------------------
    | Maximum Number of Files
    |--------------------------------------------------------------------------
    |
    | The maximum number of files that can be uploaded at once.
    | Default: 10 files
    |
    */

    'max_files' => env('UPLOAD_MAX_FILES', 10),

    /*
    |--------------------------------------------------------------------------
    | Allowed File Types
    |--------------------------------------------------------------------------
    |
    | The file extensions that are allowed for upload.
    | You can add or remove extensions as needed.
    |
    */

    'allowed_extensions' => [
        // Documents
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf',
        
        // Images
        'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp',
        
        // Archives
        'zip', 'rar', '7z', 'tar', 'gz',
        
        // Audio/Video
        'mp3', 'wav', 'mp4', 'avi', 'mov', 'wmv',
        
        // Other
        'csv', 'json', 'xml'
    ],

    /*
    |--------------------------------------------------------------------------
    | MIME Types
    |--------------------------------------------------------------------------
    |
    | The MIME types that are allowed for upload.
    | This provides an additional layer of security.
    |
    */

    'allowed_mime_types' => [
        // Documents
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'application/rtf',
        
        // Images
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/bmp',
        'image/svg+xml',
        'image/webp',
        
        // Archives
        'application/zip',
        'application/x-rar-compressed',
        'application/x-7z-compressed',
        'application/x-tar',
        'application/gzip',
        
        // Audio/Video
        'audio/mpeg',
        'audio/wav',
        'video/mp4',
        'video/x-msvideo',
        'video/quicktime',
        'video/x-ms-wmv',
        
        // Other
        'text/csv',
        'application/json',
        'application/xml',
        'text/xml'
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Path
    |--------------------------------------------------------------------------
    |
    | The base path where uploaded files will be stored.
    | This is relative to the storage/app directory.
    |
    */

    'storage_path' => 'uploads',

    /*
    |--------------------------------------------------------------------------
    | Temporary Upload Path
    |--------------------------------------------------------------------------
    |
    | The path where temporary uploads are stored before processing.
    | This is relative to the storage/app directory.
    |
    */

    'temp_path' => 'temp',
    
    // Configurações específicas para logos
    'logos' => [
        'validation_rules' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
        'max_size' => 2048, // 2MB em KB
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'svg'],
        'allowed_mimes' => ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'],
        'storage_path' => 'logos',
    ],
    
    // Configurações específicas para assinaturas
    'assinaturas' => [
        'validation_rules' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
        'max_size' => 2048, // 2MB em KB
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'svg'],
        'allowed_mimes' => ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'],
        'storage_path' => 'assinaturas',
    ],

];