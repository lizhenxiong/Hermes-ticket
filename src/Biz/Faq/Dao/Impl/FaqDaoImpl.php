<?php
namespace Hermes\Biz\Faq\Dao\Impl;

use Hermes\Biz\Faq\Dao\FaqDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class FaqDaoImpl extends GeneralDaoImpl implements FaqDao
{
    protected $table = 'faq';

    public function getFaqByQuestion($fields)
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
                'category = :category',
                'question = :question',
                'answer = :answer'
            ),
        );
    }
}