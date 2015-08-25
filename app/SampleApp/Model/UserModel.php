<?php
namespace SampleApp\Model;
use Silex\Application;

class UserModel extends Model{
    /**
     * @param Application $db
     */
    public function __construct(Application $db) {
        parent::__construct($db);
        $this->table = 'user';
        $this->field = array('name', 'password', 'fullname', 'sex', 'created_Time', 'updated_Time');
    }

    // public function die
    public function getAll($orderBy = '', $ASC_DESC = '') {
        $sql = $this->queryBuilder->select('*')
                ->from($this->table);
        if($orderBy != '') {
            $sql->orderBy($orderBy, $ASC_DESC)
                ->setFirstResult(0)
                ->setMaxResults(PERPAGE);
        } else {
            $sql->orderBy('id', 'ASC');
        }
        $users = $this->db->fetchAll($sql);
        return $users;
    }
    public function checkUser($data = array()) {
        $sql = $this->queryBuilder->select('*')
                ->from($this->table)
                ->where("name = '{$data['name']}' and password = '{$data['password']}'");
        $query = $this->db->fetchAssoc($sql);
        if($query) {
            return $query['id'];
        }
        return false;
    }

    public function checkUserName($data) {
        $sql = $this->queryBuilder->select('*')
                ->from($this->table)
                ->where("name = '{$data}'");
        $query = $this->db->fetchAssoc($sql);
        if($query) {
            return true;
        }
        return false;
    }

    public function destroy($name = '') {
        $sql = $this->db->delete('user',array('name' => $name));
        return true;
    }



}