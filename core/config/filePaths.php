<?php

return [
    'artist-images' => 'artists',
    'genre' => 'genres',
    'radio-channel-logo' => 'radios',
    'song-category-image' => 'song-category',
    'playlist-image' => 'playlist',
    'lyricist-images' => 'lyricist',
    'channel-images' => 'channel',
    'programme-images' => 'programme',
    'programme-slider' => 'banner',
    'episode-images' => 'episodes',
    'notification-images' => 'notification',
    'song-composer-images' => 'composer',
    'project-images' => 'projects',
    'product-images' => 'product',
    'song' => 'song',
    'song-audio' => 'kiki_music/mp3_files/',
    'song-smil' => 'smil',
    'front' => [
        'project' => env('IMAGE_UPLOAD_PATH', '') . '/projects/',
        'product' => env('IMAGE_UPLOAD_PATH', '') . '/product/',
        'song-composer' => env('IMAGE_UPLOAD_PATH', '') . '/composer/',
        'artist' => env('IMAGE_UPLOAD_PATH', '') . '/artists/',
        'lyricist' => env('IMAGE_UPLOAD_PATH', '') . '/lyricist/',
        'song-category' => env('IMAGE_UPLOAD_PATH', '') . '/song-category/',
        'radio-channel' => env('IMAGE_UPLOAD_PATH', '') . '/radios/',
        'playlist' => env('IMAGE_UPLOAD_PATH', '') . '/playlist/',
        'genre' => env('IMAGE_UPLOAD_PATH', '') . '/genres/',
        'song-image' => env('IMAGE_UPLOAD_PATH', '') . '/songs/',
        'song-audio' => env('IMAGE_UPLOAD_PATH', '') . '/kiki_music/mp3_files/',
        'song-smil' => env('IMAGE_UPLOAD_PATH', '') . '/smil/',
        'channel' => env('IMAGE_UPLOAD_PATH', '') . '/channel/',
        'programme' => env('IMAGE_UPLOAD_PATH', '') . '/programme/',
        'programme-slider' => env('IMAGE_UPLOAD_PATH', '') . '/banner/',
        'episode' => env('IMAGE_UPLOAD_PATH', '') . '/episodes/',
        'notification' => env('IMAGE_UPLOAD_PATH', '') . '/notification/',
    ],
];
