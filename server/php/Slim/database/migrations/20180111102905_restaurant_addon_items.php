<?php

use \Db\Migration\Migration;

class RestaurantAddonItems extends Migration
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
        $restaurant_addon_items = $this->table('restaurant_addon_items');
        $restaurant_addon_items
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('restaurant_addon_id', 'biginteger')
              ->addColumn('name','string', ['limit' => 255])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addIndex('restaurant_addon_id')
              ->addForeignKey('restaurant_addon_id', 'restaurant_addons', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('restaurant_addon_items');
    }
}
