<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Doctrine\DBAL\Schema\Table;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Doctrine\Common\ClassLoader;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new FormServiceProvider());


$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
//connect database
$configDB = array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbhost' => 'localhost',
        'dbname' => 'blog',
        'user' => 'root',
        'password' => '',
    ));
$app->register(new Silex\Provider\DoctrineServiceProvider(),$configDB);

$app->register(new Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider, array(
    "orm.em.options" => array(
        "mappings" => array(
            array(
                "type"      => "php",
                "namespace" => "Entity",
                "path"      => realpath(__DIR__."/../config/doctrine"),
            ),
        ),
    ),
));


$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
//De dang ki twig path
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => ROOT . '/resources/views/',
));
// De su dung asset trong twig
$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) {
        return sprintf(BASE_URL . '/%s', ltrim($asset, '/'));
    }));

    return $twig;
}));


$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));
$app->register(new Silex\Provider\SessionServiceProvider());


// Register controllers
$app["user.controller"] = $app->share(function () use ($app) {
    return new SampleApp\Controller\UserController($app);
});
$app["post.controller"] = $app->share(function () use ($app) {
    return new SampleApp\Controller\PostController($app);
});
$app['debug'] = true;
// Register ServiceProvider for every Objects
$app->register(new SampleApp\Providers\UserServiceProvider($app));
$app->register(new SampleApp\Providers\PostServiceProvider($app));

// 
$app->mount('/user', new SampleApp\Providers\UserControllerProvider());
$app->get('/', function () use ($app) {
    return $app->abort(404, "Page not found");
});
$app->mount('/posts', new SampleApp\Providers\PostControllerProvider());


return $app;