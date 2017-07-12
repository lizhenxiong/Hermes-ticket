<?php

use Phpmig\Migration\Migration;

class TicketItem extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $table = new Doctrine\DBAL\Schema\Table('ticket_item');
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement'=> true));
        $table->addColumn('ticketId', 'integer', array('default' => 0));
        $table->addColumn('operatorNo', 'string', array('length' => 32, 'null' => false, 'default' => '', 'comment' => '客户编号'));
        $table->addColumn('about', 'string', array('length' => 255, 'null' => false, 'default' => ''));
        $table->addColumn('role', 'string', array('length' => 32, 'null' => false , 'default' => ''));
        $table->addColumn('attachment1', 'string', array('length' => 255, 'default' => ''));
        $table->addColumn('attachment2', 'string', array('length' => 255, 'default' => ''));
        $table->addColumn('attachment3', 'string', array('length' => 255, 'default' => ''));
        $table->addColumn('attachment4', 'string', array('length' => 255, 'default' => ''));
        $table->addColumn('attachment5', 'string', array('length' => 255, 'default' => ''));
        $table->addColumn('type', 'string', array('length' => 32, 'null' => false, 'default' => ''));
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
        $container['db']->getSchemaManager()->dropTable('ticket_item');
    }
}