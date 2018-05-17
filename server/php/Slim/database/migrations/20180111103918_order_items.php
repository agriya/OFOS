<?php

use \Db\Migration\Migration;

class OrderItems extends Migration
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
        $order_items = $this->table('order_items');
        $order_items->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('order_id', 'biginteger')
              ->addColumn('restaurant_menu_id', 'biginteger')
              ->addColumn('restaurant_menu_price_id', 'biginteger',['null' => true])
              ->addColumn('quantity', 'integer')
              ->addColumn('price', 'decimal',['precision' => 10, 'scale' => 2])
              ->addColumn('total_price', 'decimal',['precision' => 10, 'scale' => 2])
              ->addIndex('order_id')
              ->addIndex('restaurant_menu_id')
              ->addIndex('restaurant_menu_price_id')
             ->addForeignKey('order_id', 'orders', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL']) 
             ->addForeignKey('restaurant_menu_id', 'restaurant_menus', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL']) 
             ->addForeignKey('restaurant_menu_price_id', 'restaurant_menu_prices', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])   
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('order_items');
    }
}
