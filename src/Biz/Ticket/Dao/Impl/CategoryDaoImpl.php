<?php
namespace Hermes\Biz\Ticket\Dao\Impl;

use Hermes\Biz\Ticket\Dao\CategoryDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class CategoryDaoImpl extends GeneralDaoImpl implements CategoryDao
{
    protected $table = 'category';

    public function getCategoryByFields($fields)
    {
        return $this->getByFields($fields);
    }

    public function declares()
    {
        return array(
            'timestamps' => array('created', 'updated'),
            'serializes' => array(),
            'conditions' => array(
                'id = :id',
                'name = :name',
                'delayedTime = :delayedTime',
                'priority = :priority'
            ),
        );
    }
}