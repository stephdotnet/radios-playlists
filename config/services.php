<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'parser' => [
        'radios' => explode(',', env('PARSER_RADIOS', '')),
        'driver' => [
            'jazzradio'          => 'jazzradio',
            'jazzradio_manouche' => 'jazzradio',
            'swissjazz'          => 'radiosfr',
            'nostalgie'          => 'nostalgie',
            'cheriefm'           => 'radiosfr',
            'rireetchansons'     => 'radiosfr',
            'rtl'                => 'rtl',
            'mock'               => 'mock',
        ],
    ],

    'spotify' => [
        'client_id'     => env('SPOTIFY_CLIENT_ID'),
        'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
        'admin_id'      => env('SPOTIFY_ADMIN_ID'),
    ],
];
