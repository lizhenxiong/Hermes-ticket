<?php 
namespace Hermes\Biz\User\Dao;

interface UserDao
{
    public function getUserByFields($fields);

    public function getUserByStatusAndWorkload();
}