<?php

use \Db\Migration\Migration;

class Settings extends Migration
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
        $settings = $this->table('settings');
        $settings
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('setting_category_id', 'biginteger',['null' => true])
              ->addColumn('name', 'string',['limit' => 255])
              ->addColumn('value', 'text',['null' => true])
              ->addColumn('description', 'text',['null' => true])
              ->addColumn('type', 'string',['limit' => 8])
              ->addColumn('label', 'string',['limit' => 255])
              ->addColumn('display_order', 'integer')
              ->addColumn('options', 'text',['null' => true])
              ->addColumn('plugin', 'string',['limit' => 255, 'null' => true])
              ->addColumn('is_front_end_access', 'boolean',['default' => false])
              ->addIndex('setting_category_id')
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('settings');
    }
}
