<?php
namespace Hermes\Biz\User\Dao\Impl;

use Hermes\Biz\User\Dao\UserDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class UserDaoImpl extends GeneralDaoImpl implements UserDao
{
    protected $table = 'user';

    public function getUserByFields($fields)
    {
        return $this->getByFields($fields);
    }

    public function getUserByStatusAndWorkload()
    {
        $sql = "SELECT * FROM {$this->table()} WHERE status = 'online' AND currentWorkload < maxWorkload ORDER BY currentWorkload ASC";
        return $this->db()->fetchAssoc($sql, array()) ?: null;
    }

    public function declares()
    {
        return array(
            'timestamps' => array('created', 'updated'),
            'serializes' => array('roles' => 'delimiter'),
            'conditions' => array(
                'id = :id',
                'username LIKE :username',
                'name LIKE :name',
                'roles LIKE :roles',
                'status = :status',
                'type = :type',
                'operatorNo = :operatorNo'
            ),
        );
    }
}