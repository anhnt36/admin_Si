<?php
namespace SampleApp\Service;

use Silex\Application;
use SampleApp\Model\UserModel;

class UserService
{
    private $user;

    public function __construct(Application $app)
    {
        $this->user = new UserModel($app);

    }

    public function getAll($per_page = '', $offset = '')
    {
        return $this->user->getAll($per_page ,$offset);
    }

    public function checkUser($data = array()) {
        return $this->user->checkUser($data);
    }

    public function checkUserName($data) {
        return $this->user->checkUserName($data);
    }

    public function insert($data = array()) {
        return $this->user->insert($data);
    }

    public function destroy($name = '') {
        return $this->user->destroy($name);
    }
}