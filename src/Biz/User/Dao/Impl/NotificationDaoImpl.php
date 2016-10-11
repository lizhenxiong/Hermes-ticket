<?php
namespace Hermes\Biz\User\Dao\Impl;

use Hermes\Biz\User\Dao\NotificationDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class NotificationDaoImpl extends GeneralDaoImpl implements NotificationDao
{
    protected $table = 'notification';

    public function declares()
    {
        return array(
            'timestamps' => array('created'),
            'serializes' => array(),
            'conditions' => array(
                'id = :id',
                'toId = :toId',
                'fromId = :fromId',
                'isRead = :isRead'
            ),
        );
    }

    public function  clearNotificationsNum($id, $fields)
    {
        $this->db()->update($this->table, $fields, array('toId' => $id));
        return $this->get($id);
    }
}