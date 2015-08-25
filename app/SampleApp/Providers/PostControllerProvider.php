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
class PostControllerProvider implements ControllerProviderInterface {
    public function connect(Application $app) {
        $post = new ControllerCollection(new Route());

        $post->match('/', "post.controller:index")->method('GET')->bind('listPosts');
        $post->match('/{id}', "post.controller:view")->method('GET|POST')->assert('id','\d+');
        $post->match('/new', "post.controller:form")->method('POST|GET')->bind('AddPost');
        $post->match('/update/{id}', "post.controller:update")->method('POST|GET')->bind('UpdatePost');
        $post->match('/destroy/{id}', "post.controller:destroy")->method('GET')->bind('DeletePost');

        return $post;
    }
}