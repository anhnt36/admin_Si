<?php
namespace SampleApp\Providers;

use Silex\Application;
use Silex\Route;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

/**
* 
*/
class UserControllerProvider implements ControllerProviderInterface {
    public function connect(Application $app) {
        $user = new ControllerCollection(new Route());

        $user->get('/', "user.controller:show");
        $user->match('/login', "user.controller:login")->method('POST|GET');
        $user->match('/show', "user.controller:show")->method('GET|POST');
        $user->match('/add', "user.controller:add")->method('POST|GET');
        $user->match('/logout', "user.controller:logout")->method('GET');

        return $user;
    }
}