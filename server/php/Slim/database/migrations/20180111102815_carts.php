<?php

use \Db\Migration\Migration;

class Carts extends Migration
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
        $carts = $this->table('carts');
        $carts->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('cookie_id', 'string',['limit' => 255])
              ->addColumn('user_id', 'biginteger')
              ->addColumn('restaurant_id', 'biginteger')
              ->addColumn('restaurant_menu_id', 'biginteger')
              ->addColumn('restaurant_menu_price_id', 'biginteger')
              ->addColumn('quantity', 'integer')
              ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('total_price', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addIndex('cookie_id')
              ->addIndex('restaurant_id')
              ->addIndex('restaurant_menu_id')
              ->addIndex('restaurant_menu_price_id')
              ->addIndex('user_id')
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addForeignKey('restaurant_id', 'restaurants', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_menu_id', 'restaurant_menus', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_menu_price_id', 'restaurant_menu_prices', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('carts');
    }
}
