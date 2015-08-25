<?php

namespace SampleApp\Providers;

use SampleApp\Service\UserService;
use Silex\Application;
use Silex\ServiceProviderInterface;

class UserServiceProvider implements ServiceProviderInterface 
{
    public function register(Application $app)
    {
        $app['user.service'] = $app->share(function() use($app) {
            return new UserService($app);
        });
    }
    public function boot(Application $app)
    {

    }
}