<?php
namespace peal\socialLogin\Facades;

use Illuminate\Support\Facades\Facade;
class GithubLogin extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'githublogin'; }
}