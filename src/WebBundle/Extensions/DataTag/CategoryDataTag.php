<?php 

namespace Hermes\WebBundle\Extensions\DataTag;

class CategoryDataTag extends BaseDataTag implements DataTag
{
    public function getData(array $arguments)
    {
        return $this->getCategoryService()->getCategory($arguments['id']);
    }

    protected function getCategoryService()
    {
        return $this->biz['category_service'];
    }
}