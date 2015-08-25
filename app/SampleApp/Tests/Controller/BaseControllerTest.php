<?php
namespace SampleApp\Tests\Controller;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

use Silex\WebTestCase;

class BaseControllerTest extends WebTestCase {

    public $app;
    public $client;
    public $dataInsertUser;
    public function __construct() {
        parent::__construct();
        $this->app = require __DIR__.'/../../../../app/app.php';
    }

    public function setUp() {
        parent::setUp();
        $this->client = static::createClient();
//        $this->client->followRedirects();
        $this->dataInsertUser = array(
            array(
                'name' => 'anhnguyen',
                'password' => '1234',
                'fullname' => 'Nguyễn Thế Anh',
                'sex' => 1,
                'created_Time' => date("Y-m-d H:i:s"),
                'updated_Time' => date("Y-m-d H:i:s")
            ),

        );
        $id = '';
        foreach($this->dataInsertUser as $dataUser) {
            $id = $this->app['user.service']->insert($dataUser);
        }
        $this->dataInsertUser[0]['id'] = $id;
    }

    public function tearDown() {
        parent::tearDown();
        foreach($this->dataInsertUser as $dataUser) {
            $this->app['user.service']->destroy($dataUser['name']);
        }
    }

    public function createApplication()
    {
        $this->app['debug'] = true;
        unset($this->app['exception_handler']);
        $this->app['session.test'] = true;
        return $this->app;
    }

    protected function assertCountFilter($controls, $crawler) {
        foreach ($controls as $control) {
            $this->assertCount(1, $crawler->filter($control), "Control $control not found");
        }
    }

    public function test() {

    }
}
