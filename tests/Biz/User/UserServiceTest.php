<?php

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class UserServiceTest extends BaseTestCase
{
    public function testGetUser()
    {
        $user = $this->getUserService()->getUser(1);
        $this->assertNull($user);
    }

    public function testCreateUser()
    {
        $user = array(
            'username' => 'zs',
            'phone' => '13888888888',
            'email' => 'hhh@hh.com',
            'name' => 'zhangsan',
            'roles' => array('ROLE_USER')
        );

        $resultUser = $this->getUserService()->createUser($user);

        $this->assertEquals('zs', $resultUser['username']);
        $this->assertEquals('13888888888', $resultUser['phone']);
        $this->assertEquals('hhh@hh.com', $resultUser['email']);
        $this->assertEquals('zhangsan', $resultUser['name']);
    }

    public function testUpdateUser()
    {
        $user = array(
            'username' => 'zs',
            'phone' => '13888888888',
            'email' => 'hhh@hh.com',
            'name' => 'zhangsan',
            'maxWorkload' => 10,
            'roles' => array('ROLE_USER')
        );
        $updateUser = array(
            'username' => 'zs',
            'phone' => '13999999999',
            'email' => 'xx@hh.com',
            'name' => 'lisi',
            'maxWorkload' => 20,
            'roles' => array('ROLE_USER')
        );
        $resultUser = $this->getUserService()->createUser($user);
        $resultUser = $this->getUserService()->updateUser($resultUser['id'], $updateUser);
        $this->assertEquals('zs',$resultUser['username']);
        $this->assertEquals('13999999999',$resultUser['phone']);
        $this->assertEquals('xx@hh.com',$resultUser['email']);
        $this->assertEquals('lisi',$resultUser['name']);
        $this->assertEquals(20,$resultUser['maxWorkload']);
        $this->assertEquals(array('ROLE_USER'),$resultUser['roles']);
    }

    public function testDeleteUser()
    {
        $user = array(
            'username' => 'zs',
            'phone' => '13888888888',
            'email' => 'hhh@hh.com',
            'name' => 'zhangsan',
            'roles' => array('ROLE_USER')
        );
        $resultUser = $this->getUserService()->createUser($user);
        $this->getUserService()->deleteUser($resultUser['id']);
        $user = $this->getUserService()->getUser($resultUser['id']);
        $this->assertNull($user);
    }

    public function testGetUserByNo()
    {
        $user = array(
            'operatorNo' => 'HT000002',
            'username' => 'zs',
            'phone' => '13888888888',
            'email' => 'hhh@hh.com',
            'name' => 'zhangsan',
            'roles' => array('ROLE_USER')
        );

        $resultUser = $this->getUserService()->createUser($user);
        $user = $this->getUserService()->getUserByNo('HT000002');

        $this->assertEquals('zhangsan',$resultUser['name']);
    }

    public function testSearchUsers()
    {
        $user = array(
            'username' => 'zs',
            'phone' => '13888888888',
            'email' => 'hhh@hh.com',
            'name' => 'zhangsan',
            'roles' => array('ROLE_USER'),
            'operatorNo' => '1'
        );
        
        $this->getUserService()->createUser($user);
        $this->getUserService()->createUser($user);
        $resultUser = $this->getUserService()->searchUsers(
            array('username' => 'zs'),
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('2',count($resultUser));
        $conditions['keywordType'] = 'username';
        $conditions['keyword'] = 'zs';
        $resultUser = $this->getUserService()->searchUsers(
            $conditions,
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('2',count($resultUser));
        $conditions['keywordType'] = 'name';
        $conditions['keyword'] = 'zhangsan';
        $resultUser = $this->getUserService()->searchUsers(
            $conditions,
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('2',count($resultUser));
        $conditions['keywordType'] = 'operatorNo';
        $conditions['keyword'] = '1';
        $resultUser = $this->getUserService()->searchUsers(
            $conditions,
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('2',count($resultUser));
    }

    public function testSearchUserCount()
    {
        $user = array(
            'username' => 'zs',
            'phone' => '13888888888',
            'email' => 'hhh@hh.com',
            'name' => 'zhangsan',
            'roles' => array('ROLE_USER'),
        );
        
        $this->getUserService()->createUser($user);
        $this->getUserService()->createUser($user);
        $count = $this->getUserService()->searchUserCount(
            array('username' => 'zs'),
            array('id', 'ASC'),
            0,
            999
        );
        $this->assertEquals('2',$count);
        $conditions['keywordType'] = 'username';
        $conditions['keyword'] = 'zs';
        $resultUser = $this->getUserService()->searchUserCount(
            $conditions
        );
        $this->assertEquals('2',$count);
    }

    public function testUpdateStatus()
    {
        $user = array(
            'username' => 'zs',
            'phone' => '13888888888',
            'email' => 'hhh@hh.com',
            'name' => 'zhangsan',
            'roles' => array('ROLE_USER'),
            'status' => 'online'
        );

        $resultUser = $this->getUserService()->createUser($user);
        $resultUser = $this->getUserService()->updateStatus($resultUser['id'], array('status' => 'offline'));

        $this->assertEquals('offline',$resultUser['status']);
    }

    public function testGetUserByUsername()
    {
        $user = array(
            'username' => 'zs',
            'name' => 'zhangsan',
            'roles' => array('ROLE_USER'),
        );

        $user = $this->getUserService()->createUser($user);
        $resultUser = $this->getUserService()->getUserByUsername('zs');
        $this->assertEquals('zs', $resultUser['username']);
        $this->assertEquals('zhangsan', $resultUser['name']);
        $this->assertEquals(array('ROLE_USER'), $resultUser['roles']);
    }

    public function testGetUserByStatusAndWorkload()
    {
        $user1 = array(
            'username' => 'zs',
            'name' => 'zhangsan',
            'status' =>'online',
            'currentWorkload' => 0,
            'maxWorkload' => 10,
            'roles' => array('ROLE_USER'),
        );
        $user2 = array(
            'username' => 'zs',
            'name' => 'zhangsan',
            'status' =>'online',
            'currentWorkload' => 1,
            'maxWorkload' => 10,
            'roles' => array('ROLE_USER'),
        );
        $user3 = array(
            'username' => 'zs',
            'name' => 'zhangsan',
            'status' =>'online',
            'currentWorkload' => 10,
            'maxWorkload' => 10,
            'roles' => array('ROLE_USER'),
        );
        $user4 = array(
            'username' => 'zs',
            'name' => 'zhangsan',
            'status' =>'offline',
            'currentWorkload' => 0,
            'maxWorkload' => 10,
            'roles' => array('ROLE_USER'),
        );
        $user1 = $this->getUserService()->createUser($user1);
        $user2 = $this->getUserService()->createUser($user2);
        $user3 = $this->getUserService()->createUser($user3);
        $user4 = $this->getUserService()->createUser($user4);
        $resultUser = $this->getUserService()->getUserByStatusAndWorkload();

        $this->assertEquals($user1['currentWorkload'], $resultUser['currentWorkload']);
        $this->assertEquals('online', $resultUser['status']);
    }

    public function testUpdateUserCurrentWorkload()
    {
        $user = array(
            'username' => 'zs',
            'name' => 'zhangsan',
            'currentWorkload' => 0,
            'roles' => array('ROLE_USER'),
        );
        $user = $this->getUserService()->createUser($user);
        $resultUser = $this->getUserService()->updateUserCurrentWorkload($user['id'], array('currentWorkload' => 2));

        $this->assertEquals(2, $resultUser['currentWorkload']);
    }

    protected function getUserService()
    {
        return self::$kernel['user_service'];
    }
}