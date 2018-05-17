<?php

use \Db\Migration\Migration;

class SettingCategories extends Migration
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
        $setting_categories = $this->table('setting_categories');
        $setting_categories
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('name', 'string',['limit' => 200])
              ->addColumn('description', 'text',['null' => true])
              ->addColumn('plugin', 'string',['limit' => 255, 'null' => true])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('setting_categories');
    }
}
