<?php

namespace peal\socialLogin\GithubLogin;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Config\Repository;

class GithubLogin{

    /**
     * Github authorize url
     *
     * @var string
     */
    public $authorize_url;

    /**
     * Access token url
     *
     * @var string
     */
    public $token_url;

    /**
     * Github API url for developers
     *
     * @var string
     */
    public $api_base_url;

    /**
     * App client id
     *
     * @var string
     */
    public $client_id;

    /**
     * App client secret
     *
     * @var string
     */
    public $client_secret;

    /**
     * Redirect url
     *
     * @var string
     */
    public $redirect_uri;

    /**
     * Hold config array
     *
     * @var array
     */
    public $config;
    
    /**
     * Construct object
     */
    public function __construct(Repository $config){

        $this->config = $config;
        
    }

    /**
     * Get client id
     *
     * @return string
     */
    public function getApiBaseUrl()
    {
        $this->api_base_url = $this->config->get('socialConfig.github.api_url_base');

        if(empty($this->api_base_url)){

            throw new \Exception('Required "api_base_url" key not supplied in config');
        }

        return $this->api_base_url;
    }

    /**
     * Get client id
     *
     * @return string
     */
    public function getClientId()
    {
        $this->client_id = $this->config->get('socialConfig.github.client_id');

        if(empty($this->client_id)){

            throw new \Exception('Required "client_id" key not supplied in config');
        }

        return $this->client_id;
    }


    /**
     * Get client secret
     *
     * @return string
     */
    public function getClientSecret()
    {
        $this->client_secret = $this->config->get('socialConfig.github.client_secret');

        if(empty($this->client_secret)){

            throw new \Exception('Required "client_secret" key not supplied in config');
        }

        return $this->client_secret;
    }

    /**
     * Get redirect uri
     *
     * @return string
     */
    public function getRedirectUri()
    {
        $this->redirect_uri = $this->config->get('socialConfig.github.call_back_url');

        if (empty($this->redirect_uri)) {
            
            throw new \Exception('Required "call_back_url" key not supplied in config');
        }

        return $this->redirect_uri;
    }


    /**
     * Get token url
     *
     * @return string
     */
    public function getTokenUrl() 
    {
        $this->token_url = $this->config->get('socialConfig.github.token_url');

        if (empty($this->token_url)) {
            
            throw new \Exception('Required "token_url" key not supplied in config');
        }

        return $this->token_url;
    }


    /**
     * Get authorize rul
     *
     * @return string
     */
    public function authorizeUrl()
    {
        $this->authorize_url = $this->config->get('socialConfig.github.authorize_url');

        if (empty($this->authorize_url)) {
            
            throw new \Exception('Required "authorize_url" key not supplied in config');
        }

        return $this->authorize_url;
    }
    
    /**
     * Get the authorize URL
     *
     * @returns a string
     */
    public function getAuthorizeURL(){
        
        $authorizeUrl = $this->authorizeUrl() . '?' . http_build_query([
            'client_id' => $this->getClientId(),
            'redirect_uri' => $this->getRedirectUri(),
            'state' => Str::random(),
            'scope' => 'user:email'
        ]);
        return redirect($authorizeUrl);
    }
    

    /**
     * Exchange token and code for an access token
     *
     * @param mixed $state
     * @param mixed $oauth_code
     * @return string
     */ 
    public function getAccessToken(Request $request){

        if (Session::has('git_access_token')) {
            return Session::get('git_access_token');
        }
        
        if (!isset($request->state) || empty($request->get('state'))) {
            throw new \Exception('State can not left empty');
        }

        if (!isset($request->code) || empty($request->get('code'))) {
            throw new \Exception('Auth code can not left empty');
        }

        $http = new Client();

        if (!$http instanceof Client) {
            throw new \Exception('Object have to GuzzleHttp client instance');
        }

        $response = $http->request('GET',$this->getTokenUrl(), [
            'query' => [
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'state' => $request->get('state'),
                'code' => $request->get('code')
            ],
            
        ]);

        $get_token_from_here = Str::before(str_replace("&", ",",$response->getBody()),",");

        Session::put('git_access_token',
            Str::afterLast($get_token_from_here,"=")
        );

        return Session::get('git_access_token');
    }
    
   
    /**
     * Make an API request
     *
     * @param string $access_token_url
     * @return mixed
     */ 
    public function gitHubApiCall($access_token){

        $http = new Client();

        if (!$http instanceof Client) {
            throw new \Exception('Object have to GuzzleHttp client instance');
        }

        $response = $http->request('GET',$this->getApiBaseUrl().'user', [
            'query' => [
                'access_token' => $access_token,
            ],
            
        ]);

        return $response->getBody();
    }

}