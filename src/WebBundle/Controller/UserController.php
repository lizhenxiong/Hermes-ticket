<?php

namespace Hermes\WebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Hermes\WebBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Hermes\Common\ArrayToolkit;
use Hermes\Common\Paginator;

class UserController extends BaseController
{
    public function assignedListAction(Request $request)
    {
        $user = $this->getCurrentUser();
        $fields = $request->query->all();
        $conditions = array(
            'operatorId' => $user['operatorId']
        );

        $conditions = array_merge($conditions, $fields);
        $count = $this->getTicketService()->searchTicketCount($conditions);
        $paginator = new Paginator(
            $request,
            $count,
            5
        );

        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('id', 'ASC'), 
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        return $this->render('WebBundle:User:assigned-ticket-list.html.twig', array(
            'tickets' => $tickets,
            'paginator' => $paginator,
            'operatorId' => $id
        ));
    }

    public function unassignedListAction(Request $request, $id)
    {
        $fields = $request->query->all();
        $conditions = array(
            'keywordType' => '',
            'keyword' => '',
            'operatorId' => 0
        );

        $conditions = array_merge($conditions, $fields);
        $ticketcount = $this->getTicketService()->searchTicketCount($conditions);
        $paginator = new Paginator(
            $request,
            $ticketcount,
            5
        );
        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('id', 'ASC'), 
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );

        return $this->render('WebBundle:User:unassigned-ticket-list.html.twig', array(
            'userId' => $id,
            'tickets' => $tickets,
            'paginator' => $paginator
        ));
    }
    
    public function delayedListAction(Request $request)
    {
        $fields = $request->query->all();
        $conditions = array(
            'keywordType' => '',
            'keyword' => '',
            'operatorId' => 0
        );

        $conditions = array_merge($conditions, $fields);
        $count = $this->getTicketService()->searchTicketCount($conditions);
        $paginator = new Paginator(
            $request,
            $count,
            5
        );
        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('id', 'ASC'), 
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );

        return $this->render('WebBundle:User:delayed-ticket-list.html.twig', array(
            'tickets' => $tickets,
            'paginator' => $paginator
        ));
    }

    public function listAction(Request $request)
    {
        $fields = $request->query->all();
        $conditions = array(
            'keywordType' => '',
            'keyword' => '',
        );

        $conditions = array_merge($conditions, $fields);
        $count = $this->getTicketService()->searchTicketCount($conditions);
        $paginator = new Paginator(
            $request,
            $count,
            5
        );
        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('id', 'ASC'), 
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );

        return $this->render('WebBundle:User:ticket-list.html.twig', array(
            'tickets' => $tickets,
            'paginator' => $paginator
        ));
    }

    public function ticketTransferAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $formData = $request->request->all();
            
            $operatorNo = $formData['operatorNo'];
            $operator = $this->getUserService()->searchUsers(array('operatorNo' => $operatorNo) , array('id', 'ASC'), 0, 999);

            $ticketIds = explode(',', $formData['selectedArrays']);
            $result = $this->getTicketService()->updateTickets($ticketIds, array('operatorId' => $operator[0]['id']));

            if (empty($result)) {
                return new JsonResponse(array('success' => false));
            }
            return new JsonResponse(array('success' => true));
        }

        return $this->render('WebBundle:User:transfer-view.html.twig');
    }

    public function getTicketAction(Request $request, $id, $userId)
    {
        $result = $this->getTicketService()->updateTicket($id, array('operatorId' => $userId,
            'status' => 'processing'
        ));

        if (empty($result)) {
            return new JsonResponse(array('success' => false));
        }
        return new JsonResponse(array('success' => true));
    }

    public function updateStatusAction(Request $request, $id)
    {
        $status = $request->request->all();
        $user = $this->getUserService()->updateStatus($id, $status);

        return new JsonResponse(array('status' => $user['status']));
    }

    protected function getTicketService()
    {
        return $this->biz['ticket_service'];
    }

    protected function getUserService()
    {
        return $this->biz['user_service'];
    }
}
