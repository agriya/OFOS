<?php

use \Db\Migration\Migration;

class RestaurantMenuPrices extends Migration
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
        $restaurant_menu_prices = $this->table('restaurant_menu_prices');
        $restaurant_menu_prices
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('restaurant_menu_id', 'biginteger')
              ->addColumn('price_type_id', 'integer',['comment' => '1 - Fixed, 2 - Size, 3 - Slice'])
              ->addColumn('price_type_name', 'string', ['null' => true])
              ->addColumn('price', 'decimal',['precision' => 10, 'scale' => 2])
              ->addIndex('price_type_id')
              ->addIndex('restaurant_menu_id')
              ->addForeignKey('restaurant_menu_id', 'restaurant_menus', 'id', ['delete'=> 'CASCADE', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('restaurant_menu_prices');
    }
}
