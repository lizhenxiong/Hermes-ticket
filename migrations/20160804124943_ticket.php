<?php

use Phpmig\Migration\Migration;

class Ticket extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $table = new Doctrine\DBAL\Schema\Table('ticket');
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement'=> true));
        $table->addColumn('userId', 'integer', array('unsigned' => true));
        $table->addColumn('title', 'string', array('length' => 255, 'null' => false, 'comment' => '标题'));
        $table->addColumn('about', 'text', array('null' => false, 'comment' => '简介'));
        $table->addColumn('category', 'string', array('length' => 255,'null' => false, 'comment' => '工单类型'));
        $table->addColumn('status', 'string', array('length' => 32,'null' => false, 'comment' => '状态(waiting,running,successful,failed)'));
        $table->addColumn('phone', 'string', array('length' => 32, 'null' => false, 'comment' => '联系电话'));
        $table->addColumn('email', 'string', array('length' => 128, 'null' => false, 'comment' => '邮箱'));
        $table->addColumn('operatorId', 'string', array('length' => 128, 'null' => false, 'comment' => '客服ID'));
        $table->addColumn('ticketNo', 'string', array('length' => 32, 'null' => false, 'comment' => '工单编号'));
        $table->addColumn('reminder', 'string', array('length' => 32, 'null' => false, 'comment' => '是否催单'));
        $table->addColumn('priority', 'string', array('length' => 32, 'null' => false, 'comment' => '优先级'));
        $table->addColumn('satisfaction', 'integer', array('length' => 10, 'null' => false, 'comment' => '满意度'));
        $table->addColumn('delayedTime', 'integer', array('length' => 10, 'null' => false, 'comment' => '滞留时间'));
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
        $container['db']->getSchemaManager()->dropTable('ticket');
    }
}