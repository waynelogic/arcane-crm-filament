<?php

return [
    'whisper' => [
        'enabled' => env('WHISPER_ENABLED', true),
        'url' => env('WHISPER_URL', 'http://127.0.0.1:5000/transcribe'),
    ],
    'gigachat' => [
        'url' => env('GIGACHAT_URL', 'https://ngw.devices.sberbank.ru:9443/api/v2/oauth'),
        'auth_key' => env('GIGACHAT_AUTH_KEY'),
        'client_id' => env('GIGACHAT_CLIENT_ID'),
        'client_secret' => env('GIGACHAT_CLIENT_SECRET'),
        'access_token' => env('GIGACHAT_TOKEN'),
        'timeout' => env('GIGACHAT_TIMEOUT', 10),
    ]
];
