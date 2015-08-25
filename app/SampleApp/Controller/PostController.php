<?php
namespace SampleApp\Controller;

use SampleApp\Controller\BaseController;
use SampleApp\Form\Type\EditPostFormType;
use SampleApp\Form\Type\AddCommentFormType;
use Silex\Application;
use Silex\Route;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\Form;


class PostController extends BaseController
{
    private $app;

    function __construct(Application $app)
    {
        $this->app = $app;
        parent::__construct($app);
    }

    public function index()
    {
        $app = $this->app;

        $posts = $app['post.service']->getAll();
        $pagination = $this->pagination(count($posts), '../posts?');

        $postsLimit = $app['post.service']->getAll(PERPAGE, $pagination['page_limit']);
        for ($i = 0; $i < count($postsLimit); $i++) {
            $postsLimit[$i]['comment'] = $app['post.service']->getAllComment($postsLimit[$i]['id']);
        }

        return $app['twig']->render('post/listPosts.twig', array(
            'pagination' => $pagination['page_links'],
            'listPost' => $postsLimit,
        ));
    }


    public function save($form, $method)
    {
        $app = $this->app;
        $data = $form->getData();

        $data['created_Time'] = $data['updated_Time'] = date("Y-m-d H:i:s");
        $data['id_user'] = $app['session']->get('user')['id'];
        $app['post.service']->insert($data);
        $app['session']->getFlashBag()->add('addPostSuccess', 'You added success !');
        return $app->redirect('../posts/');

    }

    public function form(Request $request)
    {
        $app = $this->app;
        $form = $app['form.factory']->createBuilder(new EditPostFormType())->getForm();

        $form->handleRequest($request);
        if ($request->getMethod() == 'POST') {
            if ($form->isValid()) {
                return $this->save($form, $request->getMethod());
            }
        }
        return $app['twig']->render('post/formPost.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function view($id, Request $request)
    {
        $app = $this->app;
        $dataPost = $app['post.service']->getData($id);
        $form = $app['form.factory']->createBuilder(new AddCommentFormType())->getForm();
        $dataPost[0]['comment'] = $app['post.service']->getAllComment($dataPost[0]['id']);
        $form->handleRequest($request);
        if($request->getMethod() == 'POST') {
            return $this->addComment($form->getData());
        } else {
            $form->get('id_user')->setData($app['session']->get('user')['id']);
            $form->get('id_post')->setData($id);
        }
        return $app['twig']->render('post/detailPost.twig', array(
            'form' => $form->createView(),
            'dataPost' => $dataPost
        ));
    }

    public function addComment($data)
    {
        $app = $this->app;
        $data['created_Time'] = $data['updated_Time'] = date("Y-m-d H:i:s");

        if (!empty($data['content'])) {
            $app['post.service']->addComment($data);
        }
        return $app->redirect("../posts/{$data['id_post']}");
    }

    public function update($id, Request $request)
    {

        $app = $this->app;

        $dataPost = $app['post.service']->getData($id);
        if(!$dataPost) {
            return $app->abort(404, "Post $id does not exist.");
        }
        $form = $app['form.factory']->createBuilder(new EditPostFormType())->getForm();

        $form->handleRequest($request);
        if ($request->getMethod() == 'POST') {
            if ($form->isValid()) {
                $data = $form->getData();
                $data['created_Time'] = $data['updated_Time'] = date("Y-m-d H:i:s");
                $app['session']->getFlashBag()->add('updatePostSuccess', 'You updated success !');
                $app['post.service']->edit($data);
                return $app->redirect('../../posts/');
            }
        } else {
            $form->get('title')->setData($dataPost[0]['title']);
            $form->get('content')->setData($dataPost[0]['content']);
            $form->get('id_user')->setData($dataPost[0]['id_user']);
            $form->get('id')->setData($id);
        }

        return $app['twig']->render('post/formPost.twig', array(
            'form' => $form->createView(),
            'post' => $dataPost,
        ));

    }

    public function destroy($id)
    {
        $app = $this->app;
        $dataPost = $app['post.service']->getData($id);
        if(!$dataPost) {
            return $app->abort(404, "Post $id does not exist.");
        }
        $app['session']->getFlashBag()->add('deletePostSuccess', 'You deleted success !');
        $app['post.service']->delete($id);
        return $app->redirect('../../posts/');
    }

}
