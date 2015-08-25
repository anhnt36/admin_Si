<?php
namespace SampleApp\Tests\Service;
use SampleApp\Service\UserService;
use SampleApp\Tests\Controller\BaseControllerTest;
use SampleApp\Tests\Service\BaseServiceTest;

class UserServiceTest extends BaseServiceTest {
    public $dataInsertUser;
    public function setUp()
    {
        parent::setUp();
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
        foreach($this->dataInsertUser as $dataUser) {
            $this->app['user.service']->destroy($dataUser['name']);
        }
    }

    public function testGetAll() {
        $result = count($this->app['user.service']->getAll('1', '0'));
        $this->assertGreaterThanOrEqual(1,$result);
    }
    public function testCheckUser() {
        $data = array(
            'name' => 'anhnguyen',
            'password' => '1234'
        );
        $result = $this->app['user.service']->checkUser($data);
        $this->assertEquals($this->dataInsertUser[0]['id'],$result,'Error : function checkUser (UserService)');
    }

    public function testCheckUserName() {
        $result = $this->app['user.service']->checkUserName($this->dataInsertUser[0]['name']);
        $this->assertTrue($result);
    }
}