<?php

namespace Hermes\WebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Hermes\WebBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Hermes\Common\ArrayToolkit;
use Hermes\Common\Paginator;

class TicketController extends BaseController
{
    public function addAction(Request $request)
    { 
        $user = $this->getCurrentUser();
        if($request->getMethod() == 'POST') {
            $ticket = $request->request->all();

            $ticket['userId'] = $user['id'];

            if (empty($ticket['category'])) {
                $ticket['category'] = 1;
            }

            $category = $this->getCategoryService()->getCategory($ticket['category']); 

            $ticket['priority'] = $category['priority'];
            $ticket['delayedTime'] = $category['delayedTime'] + intval(time());

            $this->getTicketService()->createTicket($ticket);

            return $this->redirectToRoute('customer_ticket_list', array('userId' => $user['id']));
        }

        $categorys = $this->getCategoryService()->findCategories();
        return $this->render('WebBundle:Ticket:add-ticket.html.twig', array(
            'user' => $user,
            'categorys' => $categorys
        ));
    }

    public function listAction(Request $request, $userId)
    {   
        $fields = $request->query->all();
        $user = $this->getCurrentUser();
        $conditions = array(
            'keywordType' => '',
            'keyword' => '',
            'userId' => $userId
        );
        $conditions = array_merge($conditions, $fields);

        $ticketCount = $this->getTicketService()->searchTicketCount($conditions);
        $paginator = new Paginator(
            $request,
            $ticketCount,
            15
        );
        $categorys = $this->getCategoryService()->findCategories();
        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('id', 'DESC'), 
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );

        return $this->render('WebBundle:Ticket:ticket-list.html.twig', array(
            'user' => $user,
            'tickets' => $tickets,
            'paginator' => $paginator,
            'categorys' => $categorys
        ));
    }

    public function customerDetailAction(Request $request, $id)
    {
        $ticket = $this->getTicketService()->getTicket($id);

        $ticketItems = $this->getTicketService()->getItemsByTicketId($ticket['id']);

        $user = $this->getCurrentUser();

        return $this->render('WebBundle:Ticket:ticket-detail.html.twig', array(
            'ticket' => $ticket,
            'ticketItems' => $ticketItems,
            'role' => 'customer',
            'user' => $user
        ));
    }

    public function complaintAction(Request $request, $id)
    {
        $ticket = $this->getTicketService()->getTicket($id);

        if ($request->getMethod() == 'POST') {
            $complaint = $request->request->all();
            $this->getTicketService()->complaintTicket($id, $complaint);
            return $this->redirect($this->generateUrl('customer_ticket_detail', array('id' => $id)));
        }

        return $this->render('WebBundle:Ticket:complaint-ticket-modal.html.twig', array(
            'ticket' => $ticket
        ));
    }

    public function reviewAction(Request $request, $id)
    {
        if ($request->getMethod() == 'POST') {
            $evaluate = $request->request->all();
            $this->getTicketService()->reviewTicket($id, $evaluate);
            return $this->redirect($this->generateUrl('customer_ticket_detail', array('id' => $id)));
        }

        $ticket = $this->getTicketService()->getTicket($id);

        return $this->render('WebBundle:Ticket:evaluate-ticket-modal.html.twig', array(
            'ticket' => $ticket
        ));
    }

    public function supplementAction(Request $request, $id)
    {
        if ($request->getMethod() == 'POST') {
            $supplement = $request->request->all();
            $this->getTicketService()->supplementTicket($id, $supplement);
            return new JsonResponse(array('success' => 'true'));
        }
    }

    public function askedAction(Request $request, $id)
    {
        if ($request->getMethod() == 'POST') {
            $asked = $request->request->all();
            $this->getTicketService()->askedTicket($id, $asked);
            return new JsonResponse(array('success' => 'true'));
        }
    }

    public function confirmAction(Request $request, $id)
    {
        $this->getTicketService()->confirmTicket($id);
        return new JsonResponse(array('success' => 'true'));
    }

    public function reminderAction(Request $request, $id)
    {
        $ticket = $this->getTicketService()->reminderTicket($id);
        $message = "您的工单编号为".$ticket['ticketNo']."被催单";
        if (!empty($ticket['operatorId'])) {
            $this->getNotificationService()->sendNotification($ticket['userId'], $ticket['operatorId'], $ticket['id'], $message);
        }       
        return new JsonResponse(array('success' => 'true'));
    }

    public function closeAction(Request $request, $id)
    {
        $closeTicket = $this->getTicketService()->closeTicket($id);
        $message = "您的工单编号为".$closeTicket['ticketNo']."已被用户关闭";
        if (!empty($closeTicket['operatorId'])) {
            $this->getNotificationService()->sendNotification($closeTicket['userId'], $closeTicket['operatorId'], $closeTicket['id'], $message);
        }
        $gotoUrl = $this->generateUrl('customer_ticket_list', array('userId' => $closeTicket['userId']));
        return new JsonResponse(array('success' => 'true', 'userId' => $closeTicket['userId'], 'gotoUrl' => $gotoUrl));
    }
    
    public function operatorDetailAction(Request $request, $id)
    {
        $ticket = $this->getTicketService()->getTicket($id);
        $user = $this->getCurrentUser();

        $ticketItems = $this->getTicketService()->findItemsWithService($ticket['id']);

        if (in_array('ROLE_DIRECTOR', $user['roles'])) {
            $ticketItems = $this->getTicketService()->searchTicketItems(array(
            'ticketId' => $id),
            array('id', 'ASC'),
            0, PHP_INT_MAX
            );
        }

        $sameCategoryTickets = $this->getTicketService()->findTicketsByCategoryAndStatus(array(
            'category' => $ticket['category'], 'status' => 'closed'), 
            array('id', 'ASC'), 
            0, 3
        );
        $otherTickets = $this->getTicketService()->findOtherTicketsByUserIdAndStatus(array(
            'userId' => $ticket['userId'], 'status' => 'closed'),
            array('id', 'ASC'),
            0, 3
        );
        $faqs = $this->getFaqService()->findFaqByCategory($ticket['category']);

        return $this->render('WebBundle:Ticket:ticket-detail.html.twig', array(
            'ticket' => $ticket,
            'ticketItems' => $ticketItems,
            'role' => 'service',
            'sameCategoryTickets' => $sameCategoryTickets,
            'otherTickets' => $otherTickets,
            'category' => $ticket['category'],
            'userId' => $ticket['userId'],
            'faqs' => $faqs,
            'user' => $user
        ));
    }

    public function sameCategoryListAction(Request $request, $category)
    {
        $user = $this->getCurrentUser();
        $conditions = array(
            'category' => $category, 
            'status' => 'closed'
        );
        $count = $this->getTicketService()->searchTicketCount($conditions);
        $paginator = new Paginator(
            $request,
            $count,
            10
        );
        $tickets = $this->getTicketService()->findTicketsByCategoryAndStatus(
            $conditions,
            array('id', 'ASC'),
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );

        return $this->render('WebBundle:Ticket:same-category-ticket.html.twig', array(
            'tickets' => $tickets,
            'paginator' => $paginator,
            'user' => $user
        ));
    }

    public function otherListAction(Request $request, $userId)
    {
        $user = $this->getCurrentUser();
        $conditions = array(
            'userId' => $userId,
            'status' => 'closed'
        );
        $count = $this->getTicketService()->searchTicketCount($conditions);
        $paginator = new Paginator(
            $request,
            $count,
            10
        );
        $tickets = $this->getTicketService()->findOtherTicketsByUserIdAndStatus(
            $conditions,
            array('id', 'ASC'),
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        return $this->render('WebBundle:Ticket:other-ticket.html.twig', array(
            'tickets' => $tickets,
            'paginator' => $paginator,
            'user' => $user
        ));
    }

    public function replyAction(Request $request, $id)
    {
        if ($request->getMethod() == 'POST') {
            $reply = $request->request->all();
            $replyTicket = $this->getTicketService()->replyTicket($id, $reply);

            $user = $this->getCurrentUser();
            $message = "您的工单编号为".$replyTicket[0]['ticketNo']."有新的回复";

            if (!empty($replyTicket[0]['userId'])) {
            $this->getNotificationService()->sendNotification($replyTicket[0]['operatorId'], $replyTicket[0]['userId'], $replyTicket[0]['id'], $message);
            }
            return new JsonResponse(array('success' => 'true'));
        }
    }

    public function autoReplyAction(Request $request, $ticketId, $faqId)
    {
        $this->getTicketService()->autoReplyTicket($ticketId, $faqId);
        return new JsonResponse(array('success' => 'true'));
    }

    public function getUserService()
    {
        return $this->biz['user_service'];
    }

    public function getTicketService()
    {
        return $this->biz['ticket_service'];
    }

    public function getNotificationService()
    {
        return $this->biz['notification_service'];
    }

    public function getFaqService()
    {
        return $this->biz['faq_service'];
    }

    public function getCategoryService()
    {
        return $this->biz['category_service'];
    }
}
