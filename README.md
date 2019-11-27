# laravel-social-login 

<h1 align="center">Laravel social OAuth2 login using google,facebook and github </h1>

<p align="center">
Using this package you can login with facebook, google and github, just create an app with facebook, google and github and setup your clientid,appid,clientsecret into your configuration file, after that setup clientid and client_secret into your application .env file .

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

## Installation

Inside your project root directory, open your terminal

```shell
composer require peal/laravel-social-login
```

Composer will automatically download all dependencies.

#### For Laravel

After complete the installation, open your app.php from config folder, paste below line inside providers array 

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