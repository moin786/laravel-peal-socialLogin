<?php

namespace peal\socialLogin\FacebookLogin;

use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Config\Repository;
use Facebook\Exceptions\FacebookAuthenticationException;

class FacebookLogin {
    
    /**
     * Config repository
     *
     * @var Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Hold facebook instance
     *
     * @var Facebook\Facebook
     */
    protected $fb;

    /**
     * Hold callback url
     *
     * @var string
     */
    protected $callbackurl;

    /**
     * Hold 
     *
     * @var string
     */
    protected $helper;

    /**
     * Hold scopes
     *
     * @var array
     */
    protected $scope;


    /**
     * Hold access token
     *
     * @var string
     */
    protected $token;

    /**
     * Booting services
     *
     * @param Facebook $fb
     * @param Repository $config
     */
    public function __construct(Facebook $fb, Repository $config) {

        $this->fb = $fb;
        $this->config = $config;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function redirectLoginUrl() {

        if ($this->fb instanceof Facebook) {

            return $this->fb->getRedirectLoginHelper();

        }

        throw new FacebookAuthenticationException("Facebook instance is missing");
    }


    /**
     * Hold callback url
     *
     * @return string
     */
    public function getCallBackUrl() {

        $this->callbackurl = $this->config->get('socialConfig.facebook.call_back_url');

        return $this->callbackurl;
    }

    /**
     * Return the redirect login helper
     *
     * @return FacebookRedirectLoginHelper
     */
    public function getHelper() {

        return $this->redirectLoginUrl();

    }

    /**
     * Return the facebook login url
     *
     * @return string
     */
    public function getLoginUrl() {

        return $this->getHelper()
                    ->getLoginUrl(
                        $this->getCallBackUrl(), 
                        $this->getScope()
                    );
    }


    /**
     * Hold scopes
     *
     * @return array
     */
    public function getScope() {

        $this->scope = $this->config->get('socialConfig.facebook.scope');

        return (array) $this->scope;
    }


    /**
     * Get access token
     *
     * @return FaceBookPersistentDataHandler
     */
    public function getAccessToken() {

        if (Session::has('fb_user_access_token')) {
            return Session::get('fb_user_access_token');
        }

        if (isset($_GET['state'])) {
            $this->getHelper()
                 ->getPersistentDataHandler()
                 ->set('state', $_GET['state']);
        }

        $this->token = $this->getHelper()
                    ->getAccessToken(
                        $this->config->get('socialConfig.facebook.call_back_url')
                    );

        Session::put('fb_user_access_token', (string) $this->token);

        return $this->token;
    }


    /**
     * Get OAuth2Client
     *
     * @return OAuth2Client
     */
    public function getOAuth2Client() {
        
        $token = $this->getAccessToken('');

        try {

            $response = $this->fb->get('/me?fields=id,name,email,picture', Session::get('fb_user_access_token'));

            return $response->getGraphUser();

        } catch (Facebook\Exceptions\FacebookSDKException $e) {

            return $e->getMessage();

        }

        if (!$token->isLongLived()) {

            $oauth_client = $this->fb->getOAuth2Client();

            
            try {
                
                $token = $oauth_client->getLongLivedAccessToken($token);
               
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                
                return $e->getMessage();
            }

            $this->fb->setDefaultAccessToken($token);
        }

        // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
        return $response->getGraphUser();

    }
}