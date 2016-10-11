<?php 
namespace Hermes\Biz\Ticket\Dao;

interface TicketDao
{
    public function findTicketsByIds($ids);
}