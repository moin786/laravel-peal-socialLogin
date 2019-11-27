<?php
return [
    'facebook' => [
        'app_id' => env('FACEBOOK_APP_ID'), // Replace {app_id} with your app id
        'app_secret' => env('FACEBOOK_APP_SECRET'),
        'default_graph_version' => env('FACEBOOK_GRAPH_VERSION'),//'v2.2',
        'call_back_url' => env('FACEBOOK_CALLBACK_URL'),
        'scope' => [
            'email'
        ]
    ],
    'google' => [
        'application_name' => env('GOOLE_CONSOLE_APPLICATION_NAME'),
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect_url' => env('GOOGLE_REDIRECT_URL'),
        'scope' => [
            'https://www.googleapis.com/auth/plus.me',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ]
    ],

    'github' => [
        'app_id' => env('GITHUB_APP_ID'),
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'authorize_url' => 'https://github.com/login/oauth/authorize',
        'token_url' => 'https://github.com/login/oauth/access_token',
        'api_url_base' => 'https://api.github.com/',
        'call_back_url' => env('GITHUB_CALLBACK_URL')
    ],
];