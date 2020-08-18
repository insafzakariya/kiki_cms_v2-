<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "s3", "rackspace"
    |
     */

    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
     */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
     */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path(),
        ],

        's3' => [
            'driver' => 's3',
            'key' => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],
        's4' => [
            'driver' => 's4',
            'key' => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],

        'rackspace' => [
            'driver' => 'rackspace',
            'username' => 'your-username',
            'key' => 'your-key',
            'container' => 'your-container',
            'endpoint' => 'https://identity.api.rackspacecloud.com/v2.0/',
            'region' => 'IAD',
            'url_type' => 'publicURL',
        ],

        'gcs' => [
            'driver' => 'gcs',
            // 'project_id' => env('GOOGLE_CLOUD_PROJECT_ID', 'your-project-id'),
            'project_id' => env('GOOGLE_CLOUD_PROJECT_ID', 'kiki-227408'),
            'key_file' => env('GOOGLE_CLOUD_KEY_FILE', './kiki-f0b907b9427f.json'), // optional: /path/to/service-account.json
            // 'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', 'your-bucket'),
            // 'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', 'samboleimages'),
            'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', 'kiki_images'),
            'path_prefix' => env('GOOGLE_CLOUD_STORAGE_PATH_PREFIX', null), // optional: /default/path/to/apply/in/bucket
            'storage_api_uri' => env('GOOGLE_CLOUD_STORAGE_API_URI', 'storage.googleapis.com'), // see: Public URLs below
        ],
        'gcs2' => [
            'driver' => 'gcs',
            // 'project_id' => env('GOOGLE_CLOUD_PROJECT_ID', 'your-project-id'),
            'project_id' => env('GOOGLE_CLOUD_PROJECT_ID', 'kiki-227408'),
            'key_file' => env('GOOGLE_CLOUD_KEY_FILE', './kiki-f0b907b9427f.json'), // optional: /path/to/service-account.json
            // 'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', 'your-bucket'),
            // 'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', 'samboleimages'),
            'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', 'kiki_music_content'),
            'path_prefix' => env('GOOGLE_CLOUD_STORAGE_PATH_PREFIX', null), // optional: /default/path/to/apply/in/bucket
            'storage_api_uri' => env('GOOGLE_CLOUD_STORAGE_API_URI', 'storage.googleapis.com'), // see: Public URLs below
        ],

    ],

];
