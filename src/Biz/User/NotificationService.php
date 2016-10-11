<?php  

namespace Hermes\Biz\User;

interface NotificationService
{    
    public function addNotification($fromId, $toId, $message);

    public function getNotificationsCount($userId);

    public function sendNotification($fromId, $toId, $ticketId, $message); 

    public function findNotificationsById($conditions, $orderBy, $start, $limit);

    public function clearNotificationsNum($id, $fields);
}