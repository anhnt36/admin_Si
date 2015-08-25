<?php
namespace SampleApp\Controller;

use SampleApp\Controller\BaseController;
use Silex\Application;
use Silex\Route;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use SampleApp\Form\Type\LoginFormType;
use SampleApp\Form\Type\AddUserFormType;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\Form;


class UserController extends BaseController
{
    private $app;

    function __construct(Application $app)
    {
        $this->app = $app;
        // parent::__construct();
    }

    public function login(Request $request)
    {
        $errors = '';
        $app = $this->app;

//        if (!empty($app['session']->get('user'))) {
//            return $app->redirect('../post/');
//        }

        $form = $app['form.factory']->createBuilder(new LoginFormType())
            ->getForm();
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST') {
//            var_dump($form->isValid());
//            die();
            if ($form->isValid()) {
                $data = $form->getData();
                if($app['user.service']->checkUser($data)) {
                    $app['session']->set('user', array(
                        'username'  => $data['name'],
                        'id'  => $app['user.service']->checkUser($data),
                    ));
                    return $this->app->redirect('../posts/');
                } else {
                    $app['session']->getFlashBag()->add('loginError', 'username or password incorrect !');
                }
            }
        }
        return $app['twig']->render('user/login.twig', array('form' => $form->createView()));
    }

    public function logout(Request $request)
    {
        $app = $this->app;
        $app['session']->remove('user');
        return $app->redirect('../user/login');
    }

    public function show() {
        $app = $this->app;

        $users = $app['user.service']->getAll();
        $pagination = $this->pagination(count($users),'../user/show?');
        $usersLimit = $app['user.service']->getAll('id', 'ASC');

        return $app['twig']->render('main.twig', array(
            'content'       => 'user/listUsers.twig',
            'pagination'    => $pagination['page_links'],
            'listUser'      => $usersLimit,
        ));
    }

    public function add(Request $request) {
        $app = $this->app;

        $form = $app['form.factory']->createBuilder(new AddUserFormType())->getForm();
        $form->handleRequest($request);
        if($request->getMethod() == 'POST') {
            if($form->isValid()) {
                $data = $form->getData();
                $data['created_Time'] = $data['updated_Time']= date("Y-m-d H:i:s");

                if(!$app['user.service']->checkUserName($data['name'])) {

                    $app['user.service']->insert($data);
                    return $app->redirect('../posts/');
                }
            }
        }

        return $app['twig']->render('main.twig', array(
            'form' => $form->createView(),
            'content'   => 'user/addUser.twig'
        ));

    }

}
