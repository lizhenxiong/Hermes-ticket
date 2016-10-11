<?php

namespace Hermes\WebBundle\Extensions\DataTag;

use Hermes\WebBundle\Extensions\DataTag\DataTag;
use Hermes\Biz\BizKernel;

abstract class BaseDataTag
{
    protected $biz;

    public function __construct($biz)
    {
        $this->biz = $biz;
    }
}
