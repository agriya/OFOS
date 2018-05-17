<?php

use \Db\Migration\Migration;

class RestaurantMenuAddonPrices extends Migration
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
        $restaurant_menu_addon_prices = $this->table('restaurant_menu_addon_prices');
        $restaurant_menu_addon_prices
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('restaurant_menu_id', 'biginteger')
              ->addColumn('restaurant_addon_id', 'biginteger',['default' => 0])
              ->addColumn('restaurant_addon_item_id', 'biginteger')
              ->addColumn('price', 'decimal',['precision' => 10, 'scale' => 2])
              ->addColumn('is_free', 'boolean', ['default' => true])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addIndex('restaurant_addon_id')
              ->addIndex('restaurant_addon_item_id')
              ->addIndex('restaurant_menu_id')
              ->addForeignKey('restaurant_menu_id', 'restaurant_menus', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_addon_id', 'restaurant_addons', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_addon_item_id', 'restaurant_addon_items', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('restaurant_menu_addon_prices');
    }
}
