<?php
namespace SampleApp\Tests\Controller;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use SampleApp\Tests\Controller\BaseControllerTest;

use Silex\WebTestCase;

class UserControllerTest extends BaseControllerTest {
    public $session;
    public function setUp() {
        parent::setUp();
    }
    public function tearDown() {
        parent::tearDown();
    }
    public function __construct() {
        parent::__construct();
    }
    public function testDisplayFormLogin() {
        $crawler = $this->client->request('GET', '/user/login');
        $controls = [
            'input[id="LoginForm_name"]',
            'input[id="LoginForm_password"]'
        ];
        $this->assertCountFilter($controls, $crawler);
    }



    public function testClickLogout() {
        $crawler = $this->client->request('GET', '/posts/');

        $buttonLogout = $crawler->selectLink('Logout')->link();

        $crawler = $this->client->click($buttonLogout);
        $this->assertEquals('/user/logout',
            $this->client->getRequest()->getRequestUri()
        );
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertNull($this->app['session']->get('user')['username'], "Error: Session when logout !");
    }
    public function testLoginWithDataFalse()
    {
        $crawler = $this->client->request('GET', '/user/login');
        $this->assertEquals(1, $crawler->filter('button:contains("Sign in")')->count());


        $form = $crawler->selectButton('Sign in')->form();
        $form['LoginForm[password]'] = '1234';
        $form['LoginForm[name]'] = 'use11111';
        $crawler1 = $this->client->submit($form);
        $controls = [
            'html:contains("username or password incorrect")'
        ];
        $this->assertCountFilter($controls, $crawler1);

        $form['LoginForm[password]'] = '1';
        $form['LoginForm[name]'] = '1';
        $crawler1 = $this->client->submit($form);
        $this->assertEquals(2, $crawler1->filter('li:contains("This value is too short. It should have 4 characters or more.")')->count());
    }

    public function testLoginWithDataTrue() {
        $crawler = $this->client->request('GET', '/user/login');
        $this->assertEquals(1, $crawler->filter('button:contains("Sign in")')->count());


        $form = $crawler->selectButton('Sign in')->form();

        $dataLogin = [
            'username' => 'user',
            'password' => '1234'
        ];
        $form['LoginForm[password]'] = $dataLogin['password'];
        $form['LoginForm[name]'] = $dataLogin['username'];
        $crawler1 = $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->assertSame(
            $dataLogin['username'],
            $this->app['session']->get('user')['username'],
            'Error : Session when Login'
        );
    }
}

?>