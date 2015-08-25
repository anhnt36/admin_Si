<?php
namespace SampleApp\Service;

use Silex\Application;
use SampleApp\Model\PostModel;

class PostService
{
    private $post;

    public function __construct(Application $app)
    {
        $this->post = new PostModel($app);

    }

    public function getAll($per_page = '',$offset = '')
    {
        return $this->post->getAll($per_page, $offset);
    }

    public function getAllComment($id_post = '')
    {
        return $this->post->getAllComment($id_post);
    }

    public function addComment($data = '')
    {
        return $this->post->addComment($data);
    }

    public function delete($id = '')
    {
        return $this->post->delete($id);
    }

    public function edit($data = '')
    {
        return $this->post->edit($data);
    }

    public function getData($id = '')
    {
        return $this->post->getData($id);
    }

    public function insert($data = array()) {
        return $this->post->insert($data);
    }
}