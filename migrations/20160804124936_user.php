<?php

use Phpmig\Migration\Migration;

class User extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $table = new Doctrine\DBAL\Schema\Table('user');
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement'=> true));
        $table->addColumn('nickname', 'string', array('length' => 255, 'null' => false, 'comment' => '用户名'));
        $table->addColumn('phone', 'string', array('length' => 32, 'null' => false, 'comment' => '联系电话'));
        $table->addColumn('email', 'string', array('length' => 128, 'null' => false, 'comment' => '邮箱'));
        $table->addColumn('smallAvatar', 'string', array('length' => 255));
        $table->addColumn('mediumAvatar', 'string', array('length' => 255));
        $table->addColumn('largeAvatar', 'string', array('length' => 255));
        $table->addColumn('operatorNo', 'string', array('length' => 32, 'null' => false, 'comment' => '客服编号'));
        $table->addColumn('name', 'string', array('length' => 128, 'null' => false, 'comment' => '姓名'));
        $table->addColumn('roles', 'string', array('length' => 128, 'null' => false, 'comment' => '角色'));
        $table->addColumn('status', 'string', array('length' => 128, 'null' => false, 'comment' => '状态'));
        $table->addColumn('salt', 'string', array('length' => 128, 'null' => false));
        $table->addColumn('password', 'string', array('length' => 128, 'null' => false, 'comment' => '密码'));
        $table->addColumn('currentWorkload', 'integer', array('length' => 10, 'null' => false, 'comment' => '当前工作量'));
        $table->addColumn('maxWorkload', 'integer', array('length' => 10, 'null' => false, 'comment' => '最大工作量'));
        $table->addColumn('created', 'integer', array('length' => 10, 'null' => false, 'comment' => '创建时间'));
        $table->addColumn('updated', 'integer', array('length' => 10, 'null' => false, 'comment' => '更新时间'));
        $table->setPrimaryKey(array('id'));
        $container['db']->getSchemaManager()->createTable($table);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();
        $container['db']->getSchemaManager()->dropTable('user');
    }
}