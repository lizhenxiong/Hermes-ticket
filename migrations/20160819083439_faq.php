<?php

use Phpmig\Migration\Migration;

class Faq extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $table = new Doctrine\DBAL\Schema\Table('faq');
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement'=> true));
        $table->addColumn('category', 'string', array('length' => 255,'null' => false, 'comment' => '类型'));
        $table->addColumn('question', 'string', array('length' => 255,'null' => false, 'comment' => '问题'));
        $table->addColumn('answer', 'string', array('length' => 255,'null' => false, 'comment' => '快捷回复模板'));
        $table->addColumn('attachment1', 'string', array('length' => 255));
        $table->addColumn('attachment2', 'string', array('length' => 255));
        $table->addColumn('attachment3', 'string', array('length' => 255));
        $table->addColumn('attachment4', 'string', array('length' => 255));
        $table->addColumn('attachment5', 'string', array('length' => 255));
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
        $container['db']->getSchemaManager()->dropTable('faq');
    }
}
