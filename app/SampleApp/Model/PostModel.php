<?php
namespace SampleApp\Model;

use Silex\Application;
use Doctrine\DBAL\Query\QueryBuilder;

class PostModel extends Model
{
    /**
     * @param Application $db
     */
    public function __construct(Application $db)
    {
        parent::__construct($db);
        $this->table = 'post';
        $this->field = array('title', 'content', 'id_user', 'created_Time', 'updated_Time');
    }

    // public function die
    public function getAll($per_page = '', $offset = '')
    {

        $sql = $this->queryBuilder->select('*,p.id,p.created_Time,p.updated_Time')->from($this->table, 'p');
        if (!empty($per_page)) {
            $sql->orderBy('p.created_Time', 'ASC')
                ->setFirstResult($offset)
                ->setMaxResults($per_page)
                ->leftJoin('p', 'user', 'u', 'p.id_user = u.id');
        }
        $post = $this->db->fetchAll($sql);

        return $post;
    }

    public function getAllComment($id_post = '')
    {
        $this->queryBuilder = new QueryBuilder($this->db);
//        $sql = $this->queryBuilder->select('*,comment.updated_Time,comment.created_Time')
//            ->from('comment')
//            ->leftJoin('comment', 'user', 'u', 'comment.id_user=u.id')
//            ->orderBy('comment.created_Time', 'ASC')
//            ->where("comment.id_post = {$id_post}");
        $sql = "SELECT * FROM comment c LEFT JOIN user u ON c.id_user=u.id WHERE c.id_post = {$id_post} ORDER BY c.created_Time ASC";
        $comment = $this->db->fetchAll($sql);
        return $comment;
    }

    public function addComment($data)
    {
        $this->db->insert('comment', array(
            'id_post' => $data['id_post'],
            'id_user' => $data['id_user'],
            'content' => $data['content'],
            'created_Time' => $data['created_Time'],
            'updated_Time' => $data['updated_Time'],
        ));
        return true;
    }

    public function getData($id = '')
    {
        $sql = $this->queryBuilder->select('*')
            ->from("{$this->table}")
            ->where("id = '{$id}'");
        return $this->db->fetchAll($sql);
    }


}