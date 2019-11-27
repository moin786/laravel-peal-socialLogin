<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;

Route::get('gitauthorize', function(){

    GithubLogin::getClientId();
    GithubLogin::getRedirectUri();
    return GithubLogin::getAuthorizeURL();
});


Route::get('gitloginsuccess', function(Request $request){
    $access_token = GithubLogin::getAccessToken($request);
    return GithubLogin::gitHubApiCall($access_token);
});


Route::get('facebookLogin', function(){
    FacebookLogin::getCallBackUrl();
    FacebookLogin::getScope();
    $login_url = FacebookLogin::getLoginUrl();
    return redirect($login_url);
});


Route::get('facebook-success', function(){
    //return FacebookLogin::getAccessToken();
    return FacebookLogin::getOAuth2Client();
});


Route::get('googleLogin', function(){
    GoogleLogin::getScopes();
    return GoogleLogin::gotoAuthUrl();
});


Route::get('google-success', function(Request $request){
    
    if ($request->has('code') && empty($request->get('state'))) {
        //return GoogleLogin::getAccessToken(trim($request->get('code')));
        return $userinfo = GoogleLogin::clientLogin(trim($request->get('code')));
    }    
});