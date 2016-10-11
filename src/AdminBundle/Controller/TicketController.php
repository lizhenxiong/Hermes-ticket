<?php 

namespace Hermes\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Hermes\WebBundle\Controller\BaseController;
use Hermes\Common\Paginator;

class TicketController extends BaseController
{
    public function indexAction(Request $request)
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
            10
        );
        $tickets = $this->getTicketService()->searchTickets(
            $conditions, 
            array('id', 'DESC'),
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        $categorys = $this->getCategoryService()->findCategories();
        return $this->render('AdminBundle:Ticket:ticket-list.html.twig', array(
            'tickets' => $tickets,
            'paginator' => $paginator,
            'categorys' => $categorys
        ));
    }

    public function changePriorityAction(Request $request, $id)
    {
        if ($request->getMethod() == 'POST') {

            $fields = $request->request->all();
            $ticket = $this->getTicketService()->changeTicketPriority($id, $fields);

            return $this->render('AdminBundle:Ticket:change-priority-tr.html.twig',array(
                'ticket' => $ticket
            ));
        }
        $ticket = $this->getTicketService()->getTicket($id);
        return $this->render('AdminBundle:Ticket:change-priority-model.html.twig',array(
            'ticket' => $ticket
        ));
    }

    public function distributeAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {

            $fields = $request->request->all();
            $result = $this->getTicketService()->distributeTickets($fields);

            if ($result =='false') {
                return new JsonResponse(array('success' => false));
            }
            return new JsonResponse(array('success' => true));
        }

        $conditions = array('roles' => '%ROLE_SERVICE%');

        $operators = $this->getUserService()->searchUsers(
            $conditions,
            array('id', 'DESC'),
            0, PHP_INT_MAX
        );

        return $this->render('AdminBundle:Ticket:distribute-tickets.html.twig', array(
            'operators' => $operators
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

    private function getCategoryService()
    {
        return $this->biz['category_service'];
    }
}