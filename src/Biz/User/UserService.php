<?php 
namespace Hermes\Biz\User;

interface UserService
{
    public function createUser($user);

    public function register($user);

    public function updateUser($id, $fields);

    public function deleteUser($id);

    public function getUser($id);

    public function getUserByUsername($username);

    public function getUserByNo($no);

    public function searchUsers($conditions, $orderBy, $start, $limit);

    public function searchUserCount($conditions);

    public function updateStatus($id, $status);

    public function getUserByStatusAndWorkload();

    public function updateUserCurrentWorkload($id, $fields);
}