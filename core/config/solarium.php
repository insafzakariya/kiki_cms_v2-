<?php

return [
    'kiki_music' => [
        'endpoint' => [
            'localhost' => [
                'host' => env('SOLR_HOST', '34.93.33.74'),
                'port' => env('SOLR_PORT', '8983'),
                'path' => env('SOLR_PATH', '/solr/'),
                'core' => env('SOLR_CORE', 'kiki_music'),
            ],
        ],
    ],
    'kiki_video' => [
        'endpoint' => [
            'localhost' => [
                'host' => env('SOLR_HOST', '34.93.33.74'),
                'port' => env('SOLR_PORT', '8983'),
                'path' => env('SOLR_PATH', '/solr/'),
                'core' => env('SOLR_CORE', 'kiki_video'),
            ],
        ],
    ],

];
