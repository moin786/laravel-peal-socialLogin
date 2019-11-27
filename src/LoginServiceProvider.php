<?php
namespace peal\socialLogin;

use Facebook\Facebook;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use peal\socialLogin\GoogleLogin\GoogleLogin;
use peal\socialLogin\GithubLogin\GithubLogin;
use peal\socialLogin\FacebookLogin\FacebookLogin;

class LoginServiceProvider extends ServiceProvider {
    
    /**
     * Register social login service
     *
     * @return void
     */
    public function register() {

        $this->registerFaceBook();
        $this->registerGoogleClient();
        $this->registerGithubClient();
    }
    
    /**
     * Booting configuration options
     *
     * @return void
     */
    public function boot() {
        require __DIR__.'/routes.php';
        
        $this->setupConfig();
    }
    
    /**
     * Merging config file
     * inside config folder
     *
     * @return void
     */
    protected function setupConfig() {
        $this->publishes([realpath(__DIR__.'/../config/socialConfig.php') => config_path('socialConfig.php')]);
        
        $this->mergeConfigFrom(realpath(__DIR__.'/../config/socialConfig.php'), 'socialConfig');
    }
    
    /**
     * Register facebook login service
     * Into Laraver IOC container
     *
     * @return void
     */
    protected function registerFaceBook() {
        $this->app->bind('facebooklogin', function(Container $app) {
           
            return new FacebookLogin(new Facebook([
                
                'app_id' => $app['config']['socialConfig.facebook.app_id'],
                'app_secret' => $app['config']['socialConfig.facebook.app_secret'],
                'default_graph_version' => $app['config']['socialConfig.facebook.default_graph_version']
            ]), $app['config']);
       });
       $this->app->alias('FacebookLogin', FacebookLogin::class);
    }
    
    /**
     * Register google login services
     * Into Laravel IOC container
     *
     * @return void
     */
    protected function registerGoogleClient() {
        $this->app->bind('googlelogin', function(Container $app) {
            return new GoogleLogin($app['config']);
       });
       $this->app->alias('GoogleLogin', GoogleLogin::class);
    }

    /**
     * Register github login services
     * Into Laravel IOC container
     *
     * @return void
     */
    protected function registerGithubClient() {
        $this->app->bind('githublogin', function(Container $app) {
            return new GithubLogin($app['config']);
       });
       $this->app->alias('GithubLogin', GithubLogin::class);
    }
}
