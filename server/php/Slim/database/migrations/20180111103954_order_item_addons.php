<?php

use \Db\Migration\Migration;

class OrderItemAddons extends Migration
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
        $order_item_addons = $this->table('order_item_addons');
        $order_item_addons->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('order_id', 'biginteger')
              ->addColumn('order_item_id', 'biginteger')
              ->addColumn('restaurant_addon_id', 'biginteger',['null' => true])
              ->addColumn('restaurant_menu_addon_price_id', 'biginteger',['null' => true])
              ->addColumn('price', 'decimal',['precision' => 10, 'scale' => 2])
              ->addIndex('order_id')
              ->addIndex('order_item_id')
              ->addIndex('restaurant_addon_id')
              ->addIndex('restaurant_menu_addon_price_id')
             ->addForeignKey('order_id', 'orders', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL']) 
             ->addForeignKey('order_item_id', 'order_items', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL']) 
             ->addForeignKey('restaurant_addon_id', 'restaurant_addons', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])   
             ->addForeignKey('restaurant_menu_addon_price_id', 'restaurant_menu_addon_prices', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])   
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('order_item_addons');
    }
}
