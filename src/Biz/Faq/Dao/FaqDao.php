<?php 
namespace Hermes\Biz\Faq\Dao;

interface FaqDao
{
    public function getFaqByQuestion($fields);
}