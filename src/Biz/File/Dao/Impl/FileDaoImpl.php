<?php 
namespace Hermes\Biz\File\Dao\Impl;

use Hermes\Biz\File\Dao\FileDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class FileDaoImpl extends GeneralDaoImpl implements FileDao
{
    protected $table = 'file';

    public function declares()
    {
        return array(
            'timestamps' => array('created', 'updated'),
            'serializes' => array(),
            'conditions' => array(
                'id = :id',
            ),
        );
    }
}