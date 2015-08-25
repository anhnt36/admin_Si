<?php
namespace SampleApp\Model;

use Silex\Application;
use Doctrine\DBAL\Query\QueryBuilder;

class Model
{
    protected $db;
    protected $queryBuilder;
    protected $field = array();
    protected $table;

    public function __construct(Application $db)
    {
        $this->db = $db['db'];
        $this->queryBuilder = new QueryBuilder($db['db']);
    }

    public function insert($data = array())
    {
        $add = array();
        foreach($this->field as $key) {
            $add["{$key}"] = $data["{$key}"];
        }
        $this->db->insert($this->table, $add);
        return $this->db->lastInsertId();
    }

    public function delete($id) {
        $sql = $this->db->delete($this->table,array('id' => $id));
        return true;
    }

    public function edit($data = '')
    {
        $update = array();
        foreach ($this->field as $key) {
            $update["{$key}"] = $data["{$key}"];
        }
        $this->db->update($this->table, $data, array('id' => $data['id']));
        return true;
    }



}