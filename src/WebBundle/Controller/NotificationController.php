<?php 

namespace Hermes\WebBundle\Controller;

use Hermes\WebBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Hermes\Common\Paginator;


class NotificationController extends BaseController
{
    public function indexAction(Request $request)
    {
        $user = $this->getCurrentUser();       
        $count = $this->getNotificationService()->getNotificationsCount(array(
            'toId' => $user['id'],
            'isRead' => 0
        ));
        return new JsonResponse(array(
            'count' => $count));
    }

    public function listAction(Request $request)
    {
        $user = $this->getCurrentUser();
        $notifications = $this->getNotificationService()->findNotificationsById(
            array('toId' => $user['id'],
            'isRead' => 0), 
            array('created', 'DESC'),
            0,
            5
        );

        foreach ($notifications as &$notification) {
            if (in_array('ROLE_SERVICE', $user['roles'])) {
                $notification['url'] = $this->generateUrl('operator_ticket_detail', array('id' => $notification['ticketId']));
            } else {
                $notification['url'] = $this->generateUrl('customer_ticket_detail', array('id' => $notification['ticketId']));
            }
        }

        return new JsonResponse(array(
            'notifications' => $notifications
        ));
    }

    public function showAction(Request $request)
    {
        $user = $this->getCurrentUser();
        $count = $this->getNotificationService()->getNotificationsCount(array(
            'toId' => $user['id']
        ));

        $paginator = new Paginator(
            $request,
            $count,
            20
        );

        $notifications = $this->getNotificationService()->findNotificationsById(
            array('toId' => $user['id']), 
            array('id', 'DESC'), 
            $paginator->getOffsetCount(), 
            $paginator->getPerPageCount()
        );


        $this->getNotificationService()->clearNotificationsNum($user['id'], array('isRead' => ''));
        return $this->render('WebBundle:WorkSpace:notification-show.html.twig',array(
            'user' => $user,
            'notifications' => $notifications,
            'paginator' => $paginator
        )); 
    }

    public function getNotificationService()
    {
        return $this->biz['notification_service'];
    }    
}

