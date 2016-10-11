<?php

namespace Hermes\WebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Hermes\Common\ArrayToolkit;
use Hermes\Common\Paginator;

class WorkSpaceController extends BaseController
{
    public function indexAction(Request $request)
    {
        $user = $this->getCurrentUser();

        return $this->render('WebBundle:WorkSpace:workspace-list.html.twig', array(
            'user' => $user
        ));
    }

    public function assignedListAction(Request $request, $userId)
    {
        $fields = $request->query->all();
        $conditions = array(
            'operatorId' => $userId
        );
        $conditions = array_merge($conditions, $fields);
        $count = $this->getTicketService()->searchTicketCount($conditions);
        $paginator = new Paginator(
            $request,
            $count,
            15
        );

        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('priority', 'DESC'), 
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );
        $categorys = $this->getCategoryService()->findCategories();
        $user = $this->biz->getUser();
        return $this->render('WebBundle:WorkSpace:ticket-assigned-list.html.twig', array(
            'user' => $user,
            'categorys' => $categorys,
            'tickets' => $tickets,
            'paginator' => $paginator,
        ));
    }

    public function unassignedListAction(Request $request)
    {
        $fields = $request->query->all();
        $conditions = array(
            'operatorId' => 0
        );
        $conditions = array_merge($conditions, $fields);
        $ticketcount = $this->getTicketService()->searchTicketCount($conditions);
        $paginator = new Paginator(
            $request,
            $ticketcount,
            15
        );
        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('priority', 'DESC'), 
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );
        $categorys = $this->getCategoryService()->findCategories();
        $user = $this->biz->getUser();
        return $this->render('WebBundle:WorkSpace:ticket-unassigned-list.html.twig', array(
            'user' => $user,
            'categorys' => $categorys,
            'tickets' => $tickets,
            'paginator' => $paginator
        ));
    }
    
    public function delayedListAction(Request $request)
    {
        $fields = $request->query->all();
        $conditions = array('nowTime' => intval(time()));

        $conditions = array_merge($conditions, $fields);

        $count = $this->getTicketService()->searchTicketCount($conditions);
        $paginator = new Paginator(
            $request,
            $count,
            15
        );
        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('priority', 'DESC'), 
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );

        foreach ($tickets as $key => $ticket) {
            if ($ticket['status'] == 'closed') {
                unset($tickets[$key]);
            }
        }

        $categorys = $this->getCategoryService()->findCategories();
        $user = $this->getCurrentUser();
        return $this->render('WebBundle:WorkSpace:ticket-delayed-list.html.twig', array(
            'user' => $user,
            'categorys' => $categorys,
            'tickets' => $tickets,
            'paginator' => $paginator
        ));
    }

    public function listAction(Request $request, $userId)
    {
        $conditions = $request->query->all();
        $count = $this->getTicketService()->searchTicketCount($conditions);
        $paginator = new Paginator(
            $request,
            $count,
            15
        );
        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('priority', 'DESC'), 
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );
        $categorys = $this->getCategoryService()->findCategories();
        $user = $this->biz->getUser();
        return $this->render('WebBundle:WorkSpace:ticket-list.html.twig', array(
            'user' => $user,
            'categorys' => $categorys,
            'tickets' => $tickets,
            'paginator' => $paginator
        ));
    }

    public function transferTicketsAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $fields = $request->request->all();
            $ticketIds = explode(',', $fields['selectedIds']);

            $operator = $this->getUserService()->getUserByNo($fields['operatorNo']);
            
            $result = $this->getTicketService()->transferTickets($ticketIds, $operator['id']);

            if (empty($result)) {
                return new JsonResponse(array('success' => false));
            }
            return new JsonResponse(array('success' => true));
        }

        return $this->render('WebBundle:WorkSpace:transfer-view.html.twig');
    }

    public function checkOperatorNoAction(Request $request)
    {
        $operatorNo = $request->query->get('operatorNo');
        $user = $this->getUserService()->getUserByNo($operatorNo);
        
        if (empty($user)) {
            return new JsonResponse(false);
        }
        return new JsonResponse(true);
    }

    public function receiveTicketAction(Request $request, $id)
    {
        $user = $this->getCurrentUser();
        $result = $this->getTicketService()->receiveTicket($id, array(
            'operatorId' => $user['id'],
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

    public function processedListAction(Request $request)
    {
        $user = $this->getCurrentUser();
        $fields = $request->query->all();
        $conditions = array(
            'keywordType' => '',
            'keyword' => '',
            'operatorId' => $user['id'],
            'status' => 'closed'
        );

        $conditions = array_merge($conditions, $fields);
        $count = $this->getTicketService()->searchTicketCount($conditions);
        $paginator = new Paginator(
            $request,
            $count,
            15
        );

        $processedTickets = $this->getTicketService()->searchProcessedTickets(
            $conditions,
            array('id', 'ASC'),
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        $categorys = $this->getCategoryService()->findCategories();
        return $this->render('WebBundle:WorkSpace:processed-ticket-list.html.twig', array(
            'tickets' => $processedTickets,
            'paginator' => $paginator,
            'user' => $user,
            'categorys' => $categorys
        ));
    }

    public function renderAssignedTicketAction(Request $request, $userId)
    {
        $conditions = array(
            'operatorId' => $userId
        );

        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('priority', 'ASC'),
            0, 9
        );
        $categorys = $this->getCategoryService()->findCategories();
        $user = $this->getCurrentUser();
        return $this->render('WebBundle:WorkSpace:workspace-assigned-ticket.html.twig', array(
            'user' => $user,
            'categorys' => $categorys,
            'tickets' => $tickets
        ));
    }

    public function renderUnassignedTicketAction(Request $request)
    {
        $conditions = array(
            'operatorId' => 0
        );
        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('priority', 'DESC'),
            0, 9
        );
        $categorys = $this->getCategoryService()->findCategories();
        $user = $this->biz->getUser();
        return $this->render('WebBundle:WorkSpace:workspace-unassigned-ticket.html.twig', array(
            'user' => $user,
            'categorys' => $categorys,
            'tickets' => $tickets
        ));
    }

    public function renderDelayedTicketAction(Request $request)
    {
        $conditions = array('nowTime' => intval(time()));

        $tickets = $this->getTicketService()->findDelayedTickets(
            $conditions, 
            array('priority', 'DESC'),
            0, 9
        );
        $categorys = $this->getCategoryService()->findCategories();
        $user = $this->biz->getUser();
        return $this->render('WebBundle:WorkSpace:workspace-delayed-ticket.html.twig', array(
            'user' => $user,
            'categorys' => $categorys,
            'tickets' => $tickets,
        ));
    }

    public function getTicketService()
    {
        return $this->biz['ticket_service'];
    }

    public function getUserService()
    {
        return $this->biz['user_service'];
    }

    public function getCategoryService()
    {
        return $this->biz['category_service'];
    }
}
