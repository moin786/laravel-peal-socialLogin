<?php
return [
    'facebook' => [
        'app_id' => env('FACEBOOK_APP_ID'), // Replace {app_id} with your app id
        'app_secret' => env('FACEBOOK_APP_SECRET'),
        'default_graph_version' => 'v2.6',
        'call_back_url' => 'http://localhost:8000/facebook-success',
        'scope' => [
            'email'
        ]
    ],
    'google' => [
        'application_name' => 'your-application-name',
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect_url' => 'http://localhost:8000/google-success',
        'scope' => [
            'https://www.googleapis.com/auth/plus.me',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ]
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'authorize_url' => 'https://github.com/login/oauth/authorize',
        'token_url' => 'https://github.com/login/oauth/access_token',
        'api_url_base' => 'https://api.github.com/',
        'call_back_url' => 'http://localhost:8000/gitloginsuccess'
    ],
];