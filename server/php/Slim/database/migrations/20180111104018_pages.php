<?php

use \Db\Migration\Migration;

class Pages extends Migration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()     
    {
        $pages = $this->table('pages');
        $pages->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('title', 'string',['limit' => 255])
              ->addColumn('slug', 'string',['limit' => 255])
              ->addColumn('content', 'text')
              ->addColumn('meta_keywords', 'string',['limit' => 255, 'null' => true])
              ->addColumn('meta_description', 'text',['null' => true])
              ->addColumn('is_active', 'boolean',['default' => true])
              ->addIndex('slug')
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('pages');
    }
}
