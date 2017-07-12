<?php

use Phpmig\Migration\Migration;

class Category extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $table = new Doctrine\DBAL\Schema\Table('category');
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement'=> true));
        $table->addColumn('priority', 'integer', array('length' => 10, 'null' => false, 'default' => 0,'comment' => '优先级'));
        $table->addColumn('name', 'string', array('length' => 255,'null' => false, 'default' => '', 'comment' => '类型名'));
        $table->addColumn('delayedTime', 'integer', array('length' => 10, 'null' => false, 'default' => 0, 'comment' => '滞留时间'));
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
        $container['db']->getSchemaManager()->dropTable('category');
    }
}
