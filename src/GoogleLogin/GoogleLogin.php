<?php

namespace peal\socialLogin\GoogleLogin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\Session;


class GoogleLogin {

    /**
     * Repository config
     *
     * @var Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Google client object
     *
     * @var \Google_Client
     */
    protected $client;

    /**
     * Hold scopes
     *
     * @var array
     */
    protected $scope;
    
    /**
     * Booting google service
     *
     * @param Repository $config
     */
    public function __construct(Repository $config) {
        
        $this->config = $config;
        $this->client = new \Google_Client();
    }
    
    
    /**
     * Get application name
     *
     * @return string
     */
    protected function getApplication() {

        if (empty($this->config->get('socialConfig.google.application_name'))) {

            throw new \Exception('Please set proper application name');

        }
        
        $this->client
            ->setApplicationName(
                $this->config->get('socialConfig.google.application_name')
            );
    }
    
    
    /**
     * Get client id
     *
     * @return GoogleClientId
     */
    protected function getClientId() {

        if ( empty($this->config->get('socialConfig.google.client_id'))) {

            throw new \Exception('Please set proper client id');
        }
        
        if ($this->client instanceof \Google_Client) {

            $this->client
                ->setClientId(
                    $this->config->get('socialConfig.google.client_id')
                );

            $this->client->getClientId();
            return $this;
        }
        
        throw new \Exception('Google client object not specified');
    }
    
    /**
     * Google client secret
     *
     * @return string
     */
    protected function getClientSecret() {

        if (empty($this->config->get('socialConfig.google.client_secret'))) {

            throw new Exception('Please set proper client secret');

        }
        
        if ($this->client instanceof \Google_client) {

            $this->client
             ->setClientSecret(
                 $this->config->get('socialConfig.google.client_secret')
             );
        
            $this->client->getClientSecret();
            return $this;
        }

        throw new \Exception('Google client object not specified');
    }
    
    
    /**
     * Get redirect url
     *
     * @return string
     */
    protected function getRedirectUrl() {

        if (empty($this->config->get('socialConfig.google.redirect_url'))) {
            throw new Exception('Please set proper redirect url');
        }
        
        if ($this->client instanceof \Google_client) {

            $this->client->setRedirectUri(
                $this->config->get('socialConfig.google.redirect_url')
            );

            $this->client->getRedirectUri();
            return $this;
        }
        
        throw new \Exception('Google client object not specified');
    }
    
    /**
     * Get scopes
     *
     * @return array
     */
    public function getScopes() {

        $this->scope = $this->config->get('socialConfig.google.scope');
        
        if (is_array($this->scope)) {
            return (array) $this->scope;
        }

        throw new \Exception('Scopes must be array');
    }
    
    /**
     * Get client scopes
     *
     * @return array
     */
    public function getClientScope() {
        if (empty($this->getScopes())) {
            return false;
        }
        $this->client->setScopes($this->getScopes());
        $this->getScopes();
        
        return $this;
    }

    /**
     * Get auth url
     *
     * @return mixed
     */
    public function gotoAuthUrl() {

        $this->getApplication();

        $this->getClientId()
             ->getClientSecret()
             ->getRedirectUrl()
             ->getClientScope();

        return redirect($this->client->createAuthUrl())->withInput();
    }


    /**
     * Get OAuth client information
     *
     * @param mixed $code
     * @return mixed
     */
    public function clientLogin($code){

        $this->getClientId()
                ->getClientSecret()
                ->getRedirectUrl()
                ->getClientScope();

        $google_oauthV2 = new \Google_Service_Oauth2($this->client);

        if (isset($code)){
                 
                 $this->client->authenticate($code);

                 if ($this->client->getAccessToken()) {
                     
                        $googleuserProfile = $google_oauthV2->userinfo->get();

                        return response()->json($googleuserProfile,200);
                }

            throw new \Exception('Invalid code provided');
        }
    }

    /**
     * Get access token
     *
     * @param mixed $code
     * @return mixed
     */

    public function getAccessToken($code) {

        $this->getClientId()
            ->getClientSecret()
            ->getRedirectUrl()
            ->getClientScope();
                 
        $google_oauthV2 = new \Google_Service_Oauth2($this->client);

        if (isset($code)) {

            $this->client->authenticate($code);

            $token = $this->client->getAccessToken();

            Session::put('google_access_token', $token['access_token']);

            return $token['google_access_token'];
        } 
        
        throw new \Exception('Authenticate code missing');
    }

}