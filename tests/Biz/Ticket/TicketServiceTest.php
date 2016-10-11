<?php

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class TicketServiceTest extends BaseTestCase
{
    public function testGetTicket()
    {
        $ticket = $this->getTicketService()->getTicket(1);
        $this->assertNull($ticket);
    }

    public function testCreateTicket()
    {
        $ticket = array(
            'title' => 'haha',
            'about' => 'xixi',
            'status' => 'waiting',
            'operatorId' => 0,
            'role' => 'customer',
        );
        $resultTicket = $this->getTicketService()->createTicket($ticket);
        $this->assertEquals('haha',$resultTicket[0]['title']);
        $this->assertEquals('xixi',$resultTicket[0]['about']);
        $this->assertEquals('1',$resultTicket[1]['id']);
    }

    public function testUpdateTicket()
    {
        $ticket = array(
            'title' => 'haha',
            'about' => 'xixi',
            'status' => 'waiting',
            'operatorId' => 0,
            'role' => 'customer',
        );
        $resultTicket = $this->getTicketService()->createTicket($ticket);
        $resultTicket = $this->getTicketService()->updateTicket($resultTicket[0]['id'],array('title'=>'heihei'));
        $this->assertEquals('heihei',$resultTicket['title']);
    }

    public function testDeleteTicket()
    {
        $ticket = array(
            'title' => 'haha',
            'about' => 'xixi',
            'status' => 'waiting',
            'operatorId' => 0,
            'role' => 'customer',
        );
        $resultTicket = $this->getTicketService()->createTicket($ticket);
        $this->getTicketService()->deleteTicket($resultTicket[0]['id']);
        $ticket = $this->getTicketService()->getTicket($resultTicket[0]['id']);
        $this->assertNull($ticket);
    }

    public function testSearchTickets()
    {
        $ticket = array(
            'userId' => '1234',
            'title' => 'haha',
            'about' => 'xixi'
        );
        $this->getTicketService()->createTicket($ticket);
        $this->getTicketService()->createTicket($ticket);
        $resultTicket = $this->getTicketService()->searchTickets(
            array('about' => 'xixi'),
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('2',count($resultTicket));
        
    }

    public function testSearchTicketCount()
    {
        $ticket = array(
            'userId' => '1234',
            'title' => 'haha',
            'about' => 'xixi'
        );
        $this->getTicketService()->createTicket($ticket);
        $resultTicket = $this->getTicketService()->searchTickets(
            array('about' => 'xixi'),
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('1',count($resultTicket));
    }

    public function testTransferTickets()
    {
        $userOne = array(
            'id' => 1,
            'operatorNo' => 'HT000001'
        );
        $userTwo = array(
            'id' => 2,
            'operatorNo' => 'HT000002'
        );
        $ticketOne = array(
            'id' => 1,
            'userId' => '123',
            'title' => 'haha',
            'about' => 'xixi',
            'operatorId' => 1
        );
        $ticketTwo = array(
            'id' => 2,
            'userId' => '124',
            'title' => 'haha',
            'about' => 'xixi',
            'operatorId' => 1
        );

        $this->getUserService()->createUser($userOne);
        $this->getUserService()->createUser($userTwo);
        $this->getTicketService()->createTicket($ticketOne);
        $this->getTicketService()->createTicket($ticketTwo);
        $this->getTicketService()->transferTickets(array(1,2), 2);
        $count = $this->getTicketService()->searchTicketCount(array('operatorId' => '2'));
        $this->assertEquals('2',$count);
    }

    public function testReceiveTicket()
    {
        $ticket = array(
            'title' => 'haha',
            'about' => 'xixi',
            'status' => 'waiting',
            'operatorId' => 0,
            'role' => 'customer',
        );
        $resultTicket = $this->getTicketService()->createTicket($ticket);
        $resultTicket = $this->getTicketService()->updateTicket($resultTicket[0]['id'],array('operatorId'=>'1'));
        $this->assertEquals('haha',$resultTicket['title']);
    }

    public function testGetTicketItem()
    {
        $ticketItem = $this->getTicketService()->getTicketItem(1);
        $this->assertNull($ticketItem);
    }
    
    public function testCreateTicketItem()
    {
        $ticket = array(
            'title' => 'haha',
            'about' => 'xixi',
            'status' => 'waiting',
            'operatorId' => 0,
            'role' => 'customer',
        );
        $ticket = $this->getTicketService()->createTicket($ticket);
        $ticketItem = array(
            'ticketId' => $ticket[0]['id'],
            'about' => 'xixi',
            'role' => 'customer'
        );
        $resultTicketItem = $this->getTicketService()->createTicketItem($ticketItem);

        $this->assertEquals('1',$resultTicketItem['ticketId']);

        $this->assertEquals('xixi',$resultTicketItem['about']);
        $this->assertEquals('customer',$resultTicketItem['role']);
    }

    public function testUpdateTicketItem()
    {
        $ticket = array(
            'title' => 'haha',
            'about' => 'xixi',
            'status' => 'waiting',
            'operatorId' => 0,
            'role' => 'customer',
        );
        $ticket = $this->getTicketService()->createTicket($ticket);
        $ticketItem = array(
            'ticketId' => $ticket[0]['id'],
            'about' => 'xixi',
            'role' => '1'
        );
        $resultTicketItem = $this->getTicketService()->createTicketItem($ticketItem);
        $resultTicketItem = $this->getTicketService()->updateTicketItem($resultTicketItem['id'],array('about'=>'heihei'));
        $this->assertEquals('heihei',$resultTicketItem['about']);
    }

    public function testDeleteTicketItem()
    {
        $ticket = array(
            'title' => 'haha',
            'about' => 'xixi',
            'status' => 'waiting',
            'operatorId' => 0,
            'role' => 'customer',
        );
        $ticket = $this->getTicketService()->createTicket($ticket);
        $ticketItem = array(
            'ticketId' => $ticket[0]['id'],
            'about' => 'xixi',
            'role' => '1'
        );
        $resultTicketItem = $this->getTicketService()->createTicketItem($ticketItem);
        $resultTicketItem = $this->getTicketService()->deleteTicketItem($resultTicketItem['id']);
        $ticketItem = $this->getTicketService()->getTicketItem($resultTicketItem['id']);
        $this->assertNull($ticketItem);
    }

    public function testGetItemsByTicketId()
    {
        $ticket = array(
            'title' => 'haha',
            'about' => 'xixi',
            'status' => 'waiting',
            'operatorId' => 0,
            'role' => 'customer',
        );
        $ticket = $this->getTicketService()->createTicket($ticket);
        $ticketItem = array(
            'ticketId' => $ticket[0]['id'],
            'about' => 'xixi',
            'role' => '1'
        );
        $resultTicketItem = $this->getTicketService()->createTicketItem($ticketItem);

        $ticketItem = $this->getTicketService()->getItemsByTicketId('123');

        $this->assertEquals('xixi',$resultTicketItem['about']);
    }

    public function testSearchTicketItems()
    {
        $ticket = array(
            'id' => '123',
            'title' => 'test title',
            'about' => 'test about'
        );
        $ticket = $this->getTicketService()->createTicket($ticket);
        $ticketItem = array(
            'ticketId' => $ticket[0]['id'],
            'about' => 'xixi',
            'role' => '1'
        );
        $this->getTicketService()->createTicketItem($ticketItem);
        $this->getTicketService()->createTicketItem($ticketItem);
        $resultTicketItem = $this->getTicketService()->searchTicketItems(
            array('about' => 'xixi'),
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('3',count($resultTicketItem));
    }

    public function testSearchTicketItemCount()
    {
        $ticket = array(
            'id' => '123',
            'title' => 'test title',
            'about' => 'test about'
        );
        $ticket = $this->getTicketService()->createTicket($ticket);
        $ticketItem = array(
            'ticketId' => $ticket[0]['id'],
            'about' => 'xixi',
            'role' => '1'
        );
        $this->getTicketService()->createTicketItem($ticketItem);
        $this->getTicketService()->createTicketItem($ticketItem);
        $this->getTicketService()->createTicketItem($ticketItem);
        $TicketItemCount = $this->getTicketService()->searchTicketItemCount(
            array('about' => 'xixi'),
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('4',$TicketItemCount);      
    }

    public function testSearchSameCategoryTickets()
    {
        $ticketOne = array(
            'id' => 1,
            'userId' => '123',
            'title' => 'haha',
            'about' => 'xixi',
            'category' => 'login'
        );
        $ticketTwo = array(
            'id' => 2,
            'userId' => '124',
            'title' => 'haha',
            'about' => 'xixi',
            'category' => 'login'
        );
        $this->getTicketService()->createTicket($ticketOne);
        $this->getTicketService()->createTicket($ticketTwo);
        $resultTicket = $this->getTicketService()->searchSameCategoryTickets(
            array('category' => 'login'),
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals(2,count($resultTicket));
    }

    public function testSearchOtherTickets()
    {
        $ticketOne = array(
            'userId' => '123',
            'title' => 'haha',
            'about' => 'xixi',
        );
        $ticketTwo = array(
            'userId' => '123',
            'title' => 'ha',
            'about' => 'xi',
        );
        $this->getTicketService()->createTicket($ticketOne);
        $this->getTicketService()->createTicket($ticketTwo);
        $resultTicket = $this->getTicketService()->searchSameCategoryTickets(
            array('userId' => '123'),
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals(2,count($resultTicket));
    }

    public function testAskedTicket()
    {
        $ticket = array(
            'title' => 'test title',
            'about' => 'test about'
        );

        $ticket = $this->getTicketService()->createTicket($ticket);

        $ticketItem = array(
            'ticketId' => $ticket[0]['id'],
            'about' => 'test about'
        );

        $result = $this->getTicketService()->askedTicket($ticket[0]['id'], $ticketItem);

        $this->assertEquals('processing', $result[0]['status']);
        $this->assertEquals('test about', $result[1]['about']);
    }

    public function testComplainTicket()
    {
        $ticket = array(
            'title' => 'test title',
            'about' => 'test about'
        );

        $ticket = $this->getTicketService()->createTicket($ticket);

        $ticketItem = array(
            'about' => 'test about'
        );

        $ticketItem = $this->getTicketService()->complaintTicket($ticket[0]['id'], $ticketItem);

        $this->assertEquals('complaint', $ticketItem['type']);
    }

    public function testReviewTicket()
    {
        $ticket = array(
            'title' => 'test title',
            'about' => 'test about'
        );

        $ticket = $this->getTicketService()->createTicket($ticket);

        $ticketItem = array(
            'ticketId' => $ticket[0]['id'],
            'about' => 'test about',
            'satisfaction' => 3
        );

        $result = $this->getTicketService()->reviewTicket($ticket[0]['id'], $ticketItem);

        $this->assertEquals(3, $result[0]['satisfaction']);
        $this->assertEquals('review', $result[1]['type']);
    }

    public function testSupplementTicket()
    {
        $ticket = array(
            'title' => 'test title',
            'about' => 'test about'
        );

        $ticket = $this->getTicketService()->createTicket($ticket);

        $ticketItem = array(
            'ticketId' => $ticket[0]['id'],
            'about' => 'test about'
        );

        $ticketItem = $this->getTicketService()->supplementTicket($ticket[0]['id'], $ticketItem);

        $this->assertEquals('supplement', $ticketItem['type']);
    }

    public function testConfirmTicket()
    {
        $ticket = array(
            'title' => 'test title',
            'about' => 'test about'
        );

        $ticket = $this->getTicketService()->createTicket($ticket);

        $ticket = $this->getTicketService()->confirmTicket($ticket[0]['id']);

        $this->assertEquals('confirmed', $ticket['status']);
    }

    public function testReminderTicket()
    {
        $ticket = array(
            'title' => 'test title',
            'about' => 'test about'
        );

        $ticket = $this->getTicketService()->createTicket($ticket);

        $ticket = $this->getTicketService()->reminderTicket($ticket[0]['id']);

        $this->assertEquals(true, $ticket['reminder']);
    }

    public function testCloseTicket()
    {
        $ticket = array(
            'title' => 'test title',
            'about' => 'test about'
        );

        $ticket = $this->getTicketService()->createTicket($ticket);

        $ticket = $this->getTicketService()->closeTicket($ticket[0]['id']);

        $this->assertEquals('closed', $ticket['status']);
    }

    public function testReplyTicket()
    {
        $ticket = array(
            'title' => 'test title',
            'about' => 'test about'
        );

        $ticket = $this->getTicketService()->createTicket($ticket);

        $ticketItem = array(
            'ticketId' => $ticket[0]['id'],
            'about' => 'test about',
            'role' => 'service'
        );

        $result = $this->getTicketService()->replyTicket($ticket[0]['id'], $ticketItem);

        $this->assertEquals('test title', $result[0]['title']);
        $this->assertEquals('test about', $result[0]['about']);

        $this->assertEquals('test about', $result[1]['about']);
        $this->assertEquals('service', $result[1]['role']);
    }

    public function testChangeTicketPriority()
    {
        $ticket = array(
            'priority' => '1',
            'title' => 'test title',
            'about' => 'test about'
        );
        $ticket = $this->getTicketService()->createTicket($ticket);
        $ticket = $this->getTicketService()->changeTicketPriority($ticket[0]['id'], array('priority' => 2));

        $this->assertEquals(2, $ticket['priority']);
    }

    public function testFindDelayedTickets()
    {
        $ticket = array(
            'delayedTime' => 1,
            'title' => 'test title',
            'about' => 'test about'
        );
        $this->getTicketService()->createTicket($ticket);
        $this->getTicketService()->createTicket($ticket);
        $tickets = $this->getTicketService()->findDelayedTickets(
            array('nowTime' => intval(time())),
            array('priority', 'ASC'),
            0,
            999
        );

        $this->assertEquals(2, count($tickets));
    }

    public function testAutoAssignTicket()
    {
        $ticket = array(
            'title' => 'haha',
            'about' => 'xixi',
            'status' => 'waiting',
            'operatorId' => 0,
            'role' => 'customer',
        );
        $ticket = $this->getTicketService()->createTicket($ticket);

        $user = array(
            'username' => 'xiaohua',
            'name' => 'xiaoming',
            'status' => 'online',
            'currentWorkload' => 0,
            'maxWorkload' => 100
        );
        $user = $this->getUserService()->createUser($user);
        $ticket = $this->getTicketService()->autoAssignTicket($ticket[0]);
        $user = $this->getUserService()->getUser($ticket['operatorId']);

        $this->assertEquals($user['id'], $ticket['operatorId']);
        $this->assertEquals('processing', $ticket['status']);
        $this->assertEquals(1, $user['currentWorkload']);
    }

    public function testDistributeTickets()
    {
        $ticket1 = array(
            'title' => 'haha',
            'about' => 'xixi',
            'status' => 'waiting',
            'operatorId' => 0,
            'role' => 'customer',
        );
        $ticket2 = array(
            'title' => 'haha',
            'about' => 'xixi',
            'status' => 'tickling',
            'operatorId' => PHP_INT_MAX,
            'role' => 'customer',
        );
        $ticket1 = $this->getTicketService()->createTicket($ticket1);
        $ticket2 = $this->getTicketService()->createTicket($ticket2);

        $user = array(
            'username' => 'xiaohua',
            'name' => 'xiaoming',
            'status' => 'online',
            'currentWorkload' => 0,
            'maxWorkload' => 100,
            'operatorNo' => 'TO111111',
        );
        $user = $this->getUserService()->createUser($user);

        $field = array(
            'selectedIds' => $ticket1[0]['id'].','.$ticket2[0]['id'],
            'operatorNo' => $user['operatorNo'],
        );

        $this->getTicketService()->distributeTickets($field);
        $ticket1 = $this->getTicketService()->getTicket($ticket1[0]['id']);
        $ticket2 = $this->getTicketService()->getTicket($ticket2[0]['id']);
        $user = $this->getUserService()->getUser($user['id']);

        $this->assertEquals('processing', $ticket1['status']);
        $this->assertEquals($user['id'], $ticket1['operatorId']);
        $this->assertEquals($user['id'], $ticket2['operatorId']);
        $this->assertEquals(2, $user['currentWorkload']);
    }

    protected function getTicketService()
    {
        return self::$kernel['ticket_service'];
    }

    protected function getUserService()
    {
        return self::$kernel['user_service'];
    }
}