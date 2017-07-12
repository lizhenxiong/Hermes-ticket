<?php

use Phpmig\Migration\Migration;

class Notification extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $table = new Doctrine\DBAL\Schema\Table('notification');
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement'=> true));
        $table->addColumn('fromId', 'integer', array('null' => false, 'default' => 0, 'comment' => '发信人ID'));
        $table->addColumn('toId', 'integer', array('null' => false, 'default' => 0, 'comment' => '收信人ID'));
        $table->addColumn('ticketId', 'integer', array('null' => false, 'default' => 0, 'comment' => '工单ID'));
        $table->addColumn('message', 'string', array('length' => 255,'null' => false, 'default' => '', 'comment' => '详细'));
        $table->addColumn('isRead', 'integer', array('null' => false, 'default' => 0, 'comment' => '已读'));
        $table->addColumn('created', 'integer', array('length' => 10, 'null' => false, 'default' => 0, 'comment' => '创建时间'));
        $table->addColumn('updated', 'integer', array('length' => 10, 'null' => false, 'default' => 0, 'comment' => '更新时间'));
        $table->setPrimaryKey(array('id'));
        $container['db']->getSchemaManager()->createTable($table);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();
        $container['db']->getSchemaManager()->dropTable('notification');
    }
}
