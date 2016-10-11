<?php 

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

/**
* 
*/
class NotificationServiceTest extends BaseTestCase
{
    public function testAddNotification()
    {
        $fromId=1;
        $toId= 2;
        $message = "你的工单已被提交";
            
        $notification = $this->getNotificationService()->addNotification($fromId, $toId, $message);
        $this->assertEquals(2,$notification['toId']);
    }
    
    /**
     *@expectedException \Exception
     */

    public function testSendNotificationException()
    {
        $fromId = null;
        $toId = 1;
        $message = "会被抛出异常";

        $notification = $this->getNotificationService()->addNotification($fromId, $toId, $message);
        $this->assertEquals("会抛出异常",$message);
    }

    public function testGetUserNotificationCount()
    {
        $userId = array(
            'toId'=> 1,
        );
        $count = $this->getNotificationService()->getNotificationsCount($userId);

        $this->assertEquals(0,$count);

        $count = $this->getNotificationService()->getNotificationsCount($userId);
        $this->assertEquals(0,$count);
    }
    
    protected function getNotificationService()
    {
        return self::$kernel['notification_service'];
    }
}