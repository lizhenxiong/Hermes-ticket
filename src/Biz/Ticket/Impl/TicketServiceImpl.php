<?php 
namespace Hermes\Biz\Ticket\Impl;

use Hermes\Biz\Ticket\TicketService;
use Hermes\Common\ArrayToolkit;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class TicketServiceImpl extends KernelAwareBaseService implements TicketService
{
    private $biz;

    public function __construct($biz)
    {
        $this->biz = $biz;
    }

    public function createTicket($ticket)
    {
        if (!ArrayToolkit::requireds($ticket, array('title'))) {
            throw new \Exception('缺少必要字段,创建工单失败!');
        }

        $ticketItem = array(
            'about' => $ticket['about'],
            'role' => 'customer',
            'attachment1' => isset($ticket['attachment1'])?$ticket['attachment1']: null,
            'attachment2' => isset($ticket['attachment2'])?$ticket['attachment2']: null,
            'attachment3' => isset($ticket['attachment3'])?$ticket['attachment3']: null,
            'attachment4' => isset($ticket['attachment4'])?$ticket['attachment4']: null,
            'attachment5' => isset($ticket['attachment5'])?$ticket['attachment5']: null
        );

        $ticket = ArrayToolkit::parts($ticket, array('userId', 'type', 'category', 'title', 'about', 'email', 'phone', 'priority' ,'delayedTime'));

        $ticket['status'] = 'waiting';
        $ticket['operatorId'] = 0;
        $ticketNo='HT'.strtotime(date('Y-m-d H:i:s'));
        $ticket['ticketNo'] = $ticketNo;

        $addTicket = $this->getTicketDao()->create($ticket);

        $ticketItem['ticketId'] = $addTicket['id'];
        $ticketItem['type'] = 'created';

        $addticketItem = $this->createTicketItem($ticketItem);

        $ticket = $this->autoAssignTicket($addTicket);
        return array($ticket,$addticketItem);
    }

    public function updateTicket($id, $fields)
    {
        return $this->getTicketDao()->update($id, $fields);
    }

    public function deleteTicket($id)
    {
        return $this->getTicketDao()->delete($id);
    }

    public function getTicket($id)
    {
        return $this->getTicketDao()->get($id);
    }

    public function searchTickets($conditions, $orderBy, $start, $limit)
    {
        $conditions = $this-> _prepareConditions($conditions);

        return $this->getTicketDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function searchTicketCount($conditions)
    {
        $conditions = $this-> _prepareConditions($conditions);

        return $this->getTicketDao()->count($conditions);
    }

    public function transferTickets($ids, $userId)
    {
        return $this->getTicketDao()->batchUpdate($ids, array('operatorId' => $userId));
    }

    public function receiveTicket($id, $diffs)
    {
        $ticket = $this->getTicket($id);

        if (empty($ticket)) {
            throw new Exception("工单不存在,无法领取!");
        }

        $ticketItem = array(
            'ticketId' => $id,
            'role' => 'service',
            'about' => '工单已被客服接手!',
            'type' => 'receive'
        );
        $this->createTicketItem($ticketItem);

        return $this->getTicketDao()->update($id, $diffs);
    }

    public function createTicketItem($ticketItem)
    {
        if (!ArrayToolkit::requireds($ticketItem, array('ticketId','about'))) {
            throw new \Exception("缺少关键字段,回复工单失败!");
        }

        $ticket = $this->getTicket($ticketItem['ticketId']);

        if (empty($ticket)) {
            throw new \Exception("工单不存在,回复工单失败!");
        }

        $ticketItem = ArrayToolkit::parts($ticketItem, array('ticketId', 'about', 'operatorNo', 
            'role', 'type','attachment1', 'attachment2', 'attachment3', 'attachment4', 'attachment5'));

        return $this->getTicketItemDao()->create($ticketItem);
    }

    public function updateTicketItem($id, $fields)
    {
        return $this->getTicketItemDao()->update($id, $fields);
    }

    public function deleteTicketItem($id)
    {
        return $this->getTicketItemDao()->delete($id);
    }

    public function getTicketItem($id)
    {
        return $this->getTicketItemDao()->get($id);
    }

    public function getItemsByTicketId($ticketId)
    {
        return $this->getTicketItemDao()->getItemsByTicketId($ticketId);
    }

    public function searchTicketItems($conditions, $orderBy, $start, $limit)
    {
        return $this->getTicketItemDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function searchTicketItemCount($conditions)
    {
        return $this->getTicketItemDao()->count($conditions);
    }

    public function findTicketsByCategoryAndStatus($conditions, $orderBy, $start, $limit)
    {
        return $this->getTicketDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function findOtherTicketsByUserIdAndStatus($conditions, $orderBy, $start, $limit)
    {
        return $this->getTicketDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function searchProcessedTickets($conditions, $orderBy, $start, $limit)
    {
        $conditions = $this-> _prepareConditions($conditions);

        return $this->getTicketDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function askedTicket($id, $ticketItem)
    {
        $ticket = $this->getTicket($id);

        if (empty($ticket)) {
            throw new Exception("工单不存在,追问失败!");
        }

        if ($ticket['status'] == 'close') {
            throw new Exception("工单以关闭,无法追问!");   
        }

        $ticketItem['ticketId'] = $ticket['id'];
        $ticketItem['type'] = 'asked';
        $ticketItem['role'] = 'customer';
        $ticketItem = $this->createTicketItem($ticketItem);

        $ticket = $this->updateTicket($id, array('status' => 'processing'));

        return array($ticket, $ticketItem);
    }

    public function complaintTicket($id, $ticketItem)
    {
        $ticket = $this->getTicket($id);

        if (empty($ticket)) {
            throw new Exception("工单不存在,投诉失败!");
        }

        $ticketItem['ticketId'] = $id;
        $ticketItem['type'] = 'complaint';
        $ticketItem['role'] = 'customer';
        $ticketItem = $this->createTicketItem($ticketItem);

        return $ticketItem;
    }

    public function reviewTicket($id, $ticketItem)
    {
        $ticket = $this->getTicket($id);
        $satisfaction = $ticketItem['satisfaction'];

        if (empty($ticket)) {
            throw new Exception("工单不存在,评价失败!");
        }

        $ticketItem['ticketId'] = $id;
        $ticketItem['type'] = 'review';
        $ticketItem['role'] = 'customer';
        $ticketItem = $this->createTicketItem($ticketItem);

        $ticket = $this->updateTicket($id, array('satisfaction' => $satisfaction));

        return array($ticket, $ticketItem);
    }

    public function supplementTicket($id, $ticketItem)
    {
        $ticket = $this->getTicket($id);

        if (empty($ticket)) {
            throw new Exception("工单不存在,评价失败!");
        }

        $ticketItem['ticketId'] = $id;
        $ticketItem['type'] = 'supplement';

        return $this->createTicketItem($ticketItem);;
    }

    public function confirmTicket($id)
    {
        $ticket = $this->getTicket($id);

        if (empty($ticket)) {
            throw new Exception("工单不存在,评价失败!");
        }

        if ($ticket['status'] == 'closed') {
            throw new Exception("工单已被关闭!");
        }

        return $this->updateTicket($id, array('status' => 'confirmed'));
    }

    public function reminderTicket($id)
    {
        $ticket = $this->getTicket($id);

        if (empty($ticket)) {
            throw new Exception("工单不存在,无法催单!");
        }

        if ($ticket['status'] == 'closed') {
            throw new Exception("工单已被关闭,无法催单!");
        }

        return $this->updateTicket($id, array('reminder' => true));
    }

    public function closeTicket($id)
    {
        $ticket = $this->getTicket($id);

        if (empty($ticket)) {
            throw new Exception("工单不存在,无法关闭!");
        }

        $ticketItem = array(
            'ticketId' => $id,
            'role' => 'customer',
            'about' => '工单以关闭',
            'type' => 'closed'
        );
        $this->createTicketItem($ticketItem);

        return $this->updateTicket($id, array('status' => 'closed'));
    }

    public function replyTicket($id, $ticketItem)
    {
        $ticket = $this->getTicket($id);

        $ticketItem['role'] = 'service';
        $ticketItem['ticketId'] = $ticket['id'];
        $ticketItem['type'] = 'reply';
        $ticketItem = $this->createTicketItem($ticketItem);

        $ticket = $this->updateTicket($id, array('status' => 'tickling'));

        if ($ticket['reminder'] == true) {
            $ticket = $this->updateTicket($id, array('status' => 'tickling', 'reminder' => false));
        }

        return array($ticket, $ticketItem);
    }

    public function autoReplyTicket($ticketId, $faqId)
    {
        $faq = $this->getFaqService()->getFaq($faqId);
        $ticketItem = array(
            'about' => $faq['answer'],
            'role' => 'service',
            'ticketId' => $ticketId,
            'attachment1' => isset($faq['attachment1'])?$faq['attachment1']: null,
            'attachment2' => isset($faq['attachment2'])?$faq['attachment2']: null,
            'attachment3' => isset($faq['attachment3'])?$faq['attachment3']: null,
            'attachment4' => isset($faq['attachment4'])?$faq['attachment4']: null,
            'attachment5' => isset($faq['attachment5'])?$faq['attachment5']: null,
            'type' => 'reply'
        );

        $this->createTicketItem($ticketItem);
        $ticket = $this->updateTicket($ticketId, array('status' => 'tickling'));

        return $ticket;
    }

    public function changeTicketPriority($id, $fields)
    {
        return $this->getTicketDao()->update($id, $fields);
    }

    public function distributeTickets($fields)
    {
        $operator = $this->getUserService()->getUserByNo($fields['operatorNo']);
        if (empty($operator)) {
            throw new Exception("客服编号不存在！");
        }
        $ticketIds = explode(',', $fields['selectedIds']);
        $tickets = $this->getTicketDao()->findTicketsByIds($ticketIds);
        $operator['currentWorkload'] += count($ticketIds);
        foreach ($tickets as $ticket) {
            if ($ticket['operatorId'] == 0) {
                $distributeTicketIds[] = $ticket['id'];
            } else {
                $transferTicketIds[] = $ticket['id'];
            }
        }

        if(isset($distributeTicketIds)) {
            $distributeTickets = $this->getTicketDao()->batchUpdate($distributeTicketIds, array('operatorId' => $operator['id'], 'status' => 'processing'));
            foreach ($distributeTicketIds as $distributeTicketId) {
                $ticketItem = array(
                    'ticketId' => $distributeTicketId,
                    'role' => 'service',
                    'about' => '工单已指派给客服!',
                    'type' => 'distribute'
                );
                $this->createTicketItem($ticketItem);
            }
        }

        if (isset($transferTicketIds)) {
            $transferTickets = $this->getTicketDao()->batchUpdate($transferTicketIds, array('operatorId' => $operator['id']));
            foreach ($transferTicketIds as $transferTicketId) {
                $ticketItem = array(
                    'ticketId' => $transferTicketId,
                    'role' => 'service',
                    'about' => '工单已转派给客服!',
                    'type' => 'transfer'
                );
                $this->createTicketItem($ticketItem);
            }
        }

        $operator = $this->getUserService()->updateUserCurrentWorkload($operator['id'], array('currentWorkload' => $operator['currentWorkload']));
        if (isset($distributeTickets) || isset($transferTickets)) {
            return 'success';
        } else {
            return 'false';
        }
    }

    public function autoAssignTicket($ticket)
    {
        $operator = $this->getUserService()->getUserByStatusAndWorkload();
        if(isset($operator)) {
            $ticket = $this->getTicketDao()->update($ticket['id'], array('operatorId' => $operator['id'], 'status' => 'processing'));
            $operator = $this->getUserService()->updateUserCurrentWorkload($operator['id'], array('currentWorkload' => (++$operator['currentWorkload'])));
        }
        return $ticket;
    }

    public function findDelayedTickets($conditions, $orderBy, $start, $limit)
    {
        $tickets = $this->getTicketDao()->search($conditions, $orderBy, $start, $limit);

        foreach ($tickets as $key => $ticket) {
            if ($ticket['status'] == 'closed') {
                unset($tickets[$key]);
            }
        }

        return $tickets;
    }

    public function findItemsWithService($ticketId)
    {
        return $this->getTicketItemDao()->findItemsWithService($ticketId);
    }

    public function findTicketsByCategory($category)
    {
        return $this->getTicketDao()->search(
            array('category' => $category),
            array('id','ASC'),
            0,
            PHP_INT_MAX
        );
    }

    protected function _prepareConditions($conditions)
    {
        if (isset($conditions['keywordType'])&&($conditions['keywordType']!='')) {
            switch ($conditions['keywordType']) {
                case 'ticketNo':
                    $conditions[$conditions['keywordType']] = "%{$conditions['keyword']}%";
                    break;
                case 'title':
                    $conditions[$conditions['keywordType']] = "%{$conditions['keyword']}%";
                    break;
                case 'username':
                    $user = $this->getUserService()->getUserByUsername($conditions['keyword']);
                    $conditions['userId'] = $user ? $user['id'] : -1;
                    break;
                case 'operator':
                    $user = $this->getUserService()->getUserByUsername($conditions['keyword']);
                    $conditions['operatorId'] = $user ? $user['id'] : -1;
                    break;
                default:
                    break;
            }
        }
        if (isset($conditions['startTime']) && $conditions['startTime'] != '') {
            $conditions['startTime'] = strtotime($conditions['startTime']);
        }
        if (isset($conditions['endTime']) && $conditions['endTime'] != '') {
            $conditions['endTime'] = strtotime($conditions['endTime']);
        }
        return $conditions;
    }

    private function getTicketDao()
    {
        return $this->biz['ticket_dao'];
    }

    private function getTicketItemDao()
    {
        return $this->biz['ticketItem_dao'];
    }

    private function getUserService()
    {
        return $this->biz['user_service'];
    }

    private function getCategoryService()
    {
        return $this->biz['category_service'];
    }

    private function getFaqService()
    {
        return $this->biz['faq_service'];
    }
}