<?php

use Phpmig\Migration\Migration;

class File extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $table = new Doctrine\DBAL\Schema\Table('file');
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement'=> true));
        $table->addColumn('uri', 'string', array('length' => 255, 'null' => false, 'comment' => '图片地址'));
        $table->addColumn('size', 'string', array('length' => 255, 'null' => false));
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
        $container['db']->getSchemaManager()->dropTable('file');
    }
}
