<?php
namespace Hermes\Biz\Ticket\Dao\Impl;

use Hermes\Biz\Ticket\Dao\TicketItemDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class TicketItemDaoImpl extends GeneralDaoImpl implements TicketItemDao
{
    protected $table = 'ticket_item';

    public function getItemsByTicketId($ticketId)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE ticketId = ?";
        return $this->db()->fetchAll($sql, array($ticketId)) ?: null;
    }

    public function findItemsWithService($ticketId)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE ticketId = ? and type != 'complaint'";
        return $this->db()->fetchAll($sql, array($ticketId)) ?: null;
    }

    public function declares()
    {
        return array(
            'timestamps' => array('created', 'updated'),
            'serializes' => array(),
            'conditions' => array(
                'id = :id',
                'name = :name',
                'status = :status',
                'type = :type',
                'ticketId = :ticketId'
            ),
        );
    }
}