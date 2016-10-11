<?php
namespace Hermes\Biz\Ticket\Dao\Impl;

use Hermes\Biz\Ticket\Dao\TicketDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class TicketDaoImpl extends GeneralDaoImpl implements TicketDao
{
    protected $table = 'ticket';

    public function batchUpdate(array $ids, array $diffs)
    {
        $sets = array_map(function ($name) {
            return "{$name} = ?";
        }, array_keys($diffs));

        $marks = str_repeat('?,', count($ids) - 1).'?';

        $sql = "UPDATE {$this->table()} SET ".implode(', ', $sets)." WHERE id IN ($marks)";

        return $this->db()->executeUpdate($sql, array_merge(array_values($diffs), $ids));
    }

    public function findTicketsByIds($ids)
    {
        return $this->findInField('id', $ids);
    }

    public function findDelayedTickets()
    {
        
    }

    public function declares()
    {
        return array(
            'timestamps' => array('created', 'updated'),
            'serializes' => array(),
            'conditions' => array(
                'id = :id',
                'ticketNo LIKE :ticketNo',
                'userId = :userId',
                'name = :name',
                'status = :status',
                'type = :type',
                'priority = :priority',
                'title LIKE :title',
                'operatorId = :operatorId',
                'created >= :startTime',
                'created <= :endTime',
                'category = :category',
                'delayedTime < :nowTime'
            ),
        );
    }
}