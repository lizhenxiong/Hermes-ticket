<?php 
namespace Hermes\Biz\User\Impl;

use Hermes\Biz\User\NotificationService;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class NotificationServiceImpl extends KernelAwareBaseService implements NotificationService
{
    private $biz;

    public function __construct($biz)
    {
        $this->biz = $biz;
    }

    public function addNotification($fromId, $toId, $message)
    {
        $notification = array(
            'fromId' => $fromId,
            'toId' => $toId,
            'message' => $message);
        $notification['message'] = htmlspecialchars_decode($notification['message']);
        return $this->getNotificationDao()->create($notification);
    }

    public function sendNotification($fromId, $toId, $ticketId, $message)
    {
        $notification = array(
            'fromId' => $fromId,
            'toId' => $toId,
            'ticketId' => $ticketId,
            'message' => $message);
        $notification['message'] = htmlspecialchars_decode($notification['message']);
        
        if (empty($fromId) || empty($toId)) {
            throw new \Exception("收件人或者发件人不存在");
        }
        if (empty($message)) {
            throw new \Exception("抱歉，不能发送空内容");          
        }
       return $this->getNotificationDao()->create($notification);      
    }

    public function getNotificationsCount($userId)
    {
        
        return $this->getNotificationDao()->count($userId);
    }

    public function findNotificationsById($conditions, $orderBy, $start, $limit)
    {
        return $this->getNotificationDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function clearNotificationsNum($id, $fields)
    {
        return $this->getNotificationDao()->clearNotificationsNum($id, array('isRead' => 1));
    }

    private function getNotificationDao()
    {
        return $this->biz['notification_dao'];
    }

    private function getUserDao()
    {
        return $this->biz['user_dao'];
    }

    private function getTicketDao()
    {
        return $this->biz['ticket_dao'];
    }
}
