<?php 
namespace Hermes\Biz\Ticket;

interface TicketService
{
    public function createTicket($ticket);

    public function updateTicket($id, $fields);

    public function deleteTicket($id);

    public function getTicket($id);

    public function searchTickets($conditions, $orderBy, $start, $limit);

    public function searchTicketCount($conditions);

    public function createTicketItem($ticketItem);

    public function updateTicketItem($id, $fields);

    public function deleteTicketItem($id);

    public function getTicketItem($id);

    public function getItemsByTicketId($ticketId);

    public function searchTicketItems($conditions, $orderBy, $start, $limit);

    public function searchTicketItemCount($conditions);

    public function transferTickets($ids, $userId);
    
    public function receiveTicket($ids, $diffs);

    public function findTicketsByCategoryAndStatus($conditions, $orderBy, $start, $limit);

    public function findOtherTicketsByUserIdAndStatus($conditions, $orderBy, $start, $limit);

    public function searchProcessedTickets($conditions, $orderBy, $start, $limit);

    public function askedTicket($id, $ticketItem);

    public function complaintTicket($id, $ticketItem);

    public function reviewTicket($id, $ticketItem);

    public function supplementTicket($id, $ticketItem);

    public function confirmTicket($id);

    public function reminderTicket($id);

    public function closeTicket($id);

    public function replyTicket($id, $ticketItem);

    public function autoReplyTicket($ticketId, $faqId);

    public function changeTicketPriority($id, $fields);

    public function findDelayedTickets($conditions, $orderBy, $start, $limit);

    public function findItemsWithService($ticketId);

    public function distributeTickets($fields);

    public function autoAssignTicket($ticket);
}