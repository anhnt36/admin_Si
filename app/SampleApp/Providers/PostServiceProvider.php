<?php

namespace SampleApp\Providers;

use Doctrine\ORM\QueryBuilder;
use SampleApp\Service\PostService;
use Silex\Application;
use Silex\ServiceProviderInterface;

class PostServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['post.service'] = $app->share(function() use($app) {
            return new PostService($app);
        });
    }
    public function boot(Application $app)
    {

    }
}