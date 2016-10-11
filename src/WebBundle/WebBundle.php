<?php

namespace Hermes\WebBundle;

use Hermes\Common\ExtensionalBundle;

class WebBundle extends ExtensionalBundle
{
    public function getEnabledExtensions()
    {
        return array('DataTag', 'DataDict');
    }
}
