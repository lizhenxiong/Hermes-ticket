<?php

namespace Hermes\WebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Hermes\WebBundle\Controller\BaseController;
use Hermes\Common\ArrayToolkit;
use Hermes\Common\Paginator;


class DefaultController extends BaseController
{
    public function indexAction(Request $request)
    {
        $user = $this->getCurrentUser();  
        $conditions = array();
        $ticketCount = $this->getTicketService()->searchTicketCount($conditions);

        $paginator = new Paginator(
            $request,
            $ticketCount,
            5
        );
        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('id', 'ASC'), 
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );
        return $this->render('WebBundle:Default:index.html.twig', array(
            'tickets' => $tickets,
            'paginator' => $paginator,
            'user' => $user
        ));
    }

    private function getUserService()
    {
        return $this->biz['user_service'];
    }

    private function getTicketService()
    {
        return $this->biz['ticket_service'];
    }
}
