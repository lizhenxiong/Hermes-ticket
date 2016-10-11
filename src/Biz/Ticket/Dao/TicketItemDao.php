<?php 
namespace Hermes\Biz\Ticket\Dao;

interface TicketItemDao
{
    public function getItemsByTicketId($ticketId);

    public function findItemsWithService($ticketId);
}