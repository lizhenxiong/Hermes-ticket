<?php 
namespace Hermes\Biz\User\Impl;

use Hermes\Biz\User\UserService;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class UserServiceImpl extends KernelAwareBaseService implements UserService
{
    private $biz;

    public function __construct($biz)
    {
        $this->biz = $biz;
    }

    public function createUser($user)
    {
        return $this->getUserDao()->create($user);
    }

    public function register($user)
    {
        $user['salt'] = md5(time().mt_rand(0, 1000));
        $user['password'] = $this->biz['password_encoder']->encodePassword($user['password'], $user['salt']);
        if (empty($user['roles'])) {
            $user['roles'] = array('ROLE_USER');
        }
        return $this->getUserDao()->create($user);
    }

    public function updateUser($id, $fields)
    {
        $this->validate($fields);
        if (isset($fields['roles'])) {
            if (in_array("ROLE_SERVICE",$fields['roles']) && empty($fields['operatorNo'])) {
                $condition=array('roles' =>'ROLE_SERVICE');
                $condition['roles'] ="%{$condition['roles']}%";
                $fields['operatorNo'] = "TO".substr($this->getUserDao()->count($condition)+1000000 ,1,6);
            }
        }
        return $this->getUserDao()->update($id, $fields);
    }

    public function deleteUser($id)
    {
        return $this->getUserDao()->delete($id);
    }

    public function getUser($id)
    {
        return $this->getUserDao()->get($id);
    }

    public function getUserByUsername($username)
    {
        return $this->getUserDao()->getUserByFields(array('username' => $username));
    }

    public function getUserByNo($no)
    {
        return $this->getUserDao()->getUserByFields(array('operatorNo' => $no));
    }

    public function searchUsers($conditions, $orderBy, $start, $limit)
    {       
        return $this->getUserDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function searchUserCount($conditions)
    {
        return $this->getUserDao()->count($conditions);
    }

    public function updateStatus($id, $fields)
    {
        return $this->getUserDao()->update($id, $fields);
    }

    public function getUserByStatusAndWorkload()
    {
        return $this->getUserDao()->getUserByStatusAndWorkload();
    }

    protected function validate($user)
    {
        if(empty($user['name'])) {
            throw new \Exception("请输入姓名"); 
        }

        if(empty($user['phone'])) {
            throw new \Exception("请输入手机号"); 
        }

        if (!preg_match('/^1[3|4|5|7|8]\d{9}$/',$user['phone'])) {
            throw new \Exception("手机格式有误");
        }

        if (!preg_match('/^\d{1,4}$/',$user['maxWorkload'])) {
            throw new \Exception("请输入0-4位数字");
        }

        if(empty($user['email'])) {
            throw new \Exception("请输入邮箱"); 
        }

        if(!preg_match("/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/", $user['email'])){
            throw new \Exception("邮箱格式不正确"); 
        } 
       
    }

    public function updateUserCurrentWorkload($id, $fields)
    {
        return $this->getUserDao()->update($id, $fields);
    }

    private function getUserDao()
    {
        return $this->biz['user_dao'];
    }

}