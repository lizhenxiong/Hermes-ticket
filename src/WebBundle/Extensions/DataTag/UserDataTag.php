<?php 

namespace Hermes\WebBundle\Extensions\DataTag;

class UserDataTag extends BaseDataTag implements DataTag
{
    public function getData(array $arguments)
    {
        return $this->getUserService()->getUser($arguments['userId']);
    }

    protected function getUserService()
    {
        return $this->biz['user_service'];
    }
}