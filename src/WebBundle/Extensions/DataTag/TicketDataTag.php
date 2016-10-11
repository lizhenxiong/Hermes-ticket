<?php 

namespace Hermes\WebBundle\Extensions\DataTag;

class TicketDataTag extends BaseDataTag implements DataTag
{
    public function getData(array $arguments)
    {
        return $this->getTicketService()->getTicket($arguments['ticketId']);
    }

    protected function getTicketService()
    {
        return $this->biz['ticket_service'];
    }
}