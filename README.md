# laravel-social-login 

<h1 align="center">Laravel social OAuth2 login using google,facebook and github </h1>
<p>
Using this package you can login with facebook, google and github, just create an app with facebook, google and github and setup your clientid,appid,clientsecret into your configuration file, after that setup clientid and client_secret into your application .env file .

</p>

### Installation

Inside your project root directory, open your terminal

```shell
composer require peal/laravel-social-login
```

Composer will automatically download laravel-social-login, then run below command from your command prompt running from project root, then socialConfig.php file will be placed into application config folder.


```php
$ php artisan vendor:publish

 Which provider or tag's files would you like to publish?:
  [0 ] Publish files from all providers and tags listed below
  [1 ] Provider: Facade\Ignition\IgnitionServiceProvider
  [2 ] Provider: Fideloper\Proxy\TrustedProxyServiceProvider
  [3 ] Provider: Illuminate\Foundation\Providers\FoundationServiceProvider
  [4 ] Provider: Illuminate\Mail\MailServiceProvider
  [5 ] Provider: Illuminate\Notifications\NotificationServiceProvider
  [6 ] Provider: Illuminate\Pagination\PaginationServiceProvider
  [7 ] Provider: Laravel\Tinker\TinkerServiceProvider
  [8 ] Provider: peal\socialLogin\LoginServiceProvider
  [9 ] Tag: flare-config
  [10] Tag: ignition-config
  [11] Tag: laravel-errors
  [12] Tag: laravel-mail
  [13] Tag: laravel-notifications
  [14] Tag: laravel-pagination
 > 8


```
<p>
Openup your socialConfig.php file inside config folder and change your call_back_url or redirect_url localhost to production url.

```php
return [
    'facebook' => [
        'app_id' => env('FACEBOOK_APP_ID'), //Don't change
        'app_secret' => env('FACEBOOK_APP_SECRET'), //Don't change
        'default_graph_version' => 'v2.6',
        'call_back_url' => 'http://localhost:8000/facebook-success', //Put your production call back url
        'scope' => [
            'email'
        ]
    ],
    'google' => [
        'application_name' => 'your-application-name',
        'client_id' => env('GOOGLE_CLIENT_ID'), //Don't change
        'client_secret' => env('GOOGLE_CLIENT_SECRET'), //Don't change
        'redirect_url' => 'http://localhost:8000/google-success', //Put your production redirect url
        'scope' => [
            'https://www.googleapis.com/auth/plus.me',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ]
    ],
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'), //Don't change
        'client_secret' => env('GITHUB_CLIENT_SECRET'), //Don't change
        'authorize_url' => 'https://github.com/login/oauth/authorize',
        'token_url' => 'https://github.com/login/oauth/access_token',
        'api_url_base' => 'https://api.github.com/',
        'call_back_url' => 'http://localhost:8000/gitloginsuccess' //Put your production call back url
    ],
];
```
</p>

### Put below environment variable inside .env file
<p align="center">

```shell
#Facebook Login
FACEBOOK_APP_ID=
FACEBOOK_APP_SECRET=

#Google Login
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=

#Github Login
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=

```
</p>

#### For Laravel Old version

After complete the installation, open your app.php from config folder, paste below line inside providers array, if you are using old laravel version. 

```php
peal\socialLogin\LoginServiceProvider::class,
```
### For Laravel version befor Auto Discovery

For Facade support , paste below line inside aliases array

```php
'FacebookLogin' => peal\socialLogin\Facades\FacebookLogin::class,
```

```php
'GoogleLogin' => peal\socialLogin\Facades\GoogleLogin::class,
```

```php
'GithubLogin' => peal\socialLogin\Facades\GithubLogin::class,
```

### USAGES (Inside your route or controller) 
```php 
    Route::get('gitauthorize', function(){

        GithubLogin::getClientId();
        GithubLogin::getRedirectUri();
        return GithubLogin::getAuthorizeURL();
    });


    Route::get('gitloginsuccess', function(Request $request){
        $access_token = GithubLogin::getAccessToken($request);

        //Save this information into your database user table
        return GithubLogin::gitHubApiCall($access_token);
    });


    Route::get('facebookLogin', function(){
        FacebookLogin::getCallBackUrl();
        FacebookLogin::getScope();
        $login_url = FacebookLogin::getLoginUrl();
        return redirect($login_url);
    });


    Route::get('facebook-success', function(){
        //Save this information into your database user table
        return FacebookLogin::getOAuth2Client();
    });


    Route::get('googleLogin', function(){
        GoogleLogin::getScopes();
        return GoogleLogin::gotoAuthUrl();
    });


    Route::get('google-success', function(Request $request){
        
        if ($request->has('code') && empty($request->get('state'))) {
            //return GoogleLogin::getAccessToken(trim($request->get('code')));

            //Save this information into your database user table
            return $userinfo = GoogleLogin::clientLogin(trim($request->get('code')));
        }    
    });

```

### Author

[Mohammed Minuddin(Peal)](https://moinshareidea.wordpress.com)