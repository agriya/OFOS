<?php

use \Db\Migration\Migration;

class RestaurantDeliveryPersonOrders extends Migration
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
        $restaurant_delivery_person_orders = $this->table('restaurant_delivery_person_orders');
        $restaurant_delivery_person_orders
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('order_id', 'biginteger')
              ->addColumn('restaurant_id', 'biginteger')
              ->addColumn('restaurant_branch_id', 'biginteger',['default' => 0])
              ->addColumn('restaurant_supervisor_id', 'biginteger',['default' => 0])
              ->addColumn('restaurant_delivery_person_id', 'biginteger')
              ->addColumn('comments', 'text', ['null' => true])
              ->addColumn('is_delivered', 'boolean', ['default' => false])
              ->addIndex('order_id')
              ->addIndex('restaurant_branch_id')
              ->addIndex('restaurant_delivery_person_id')
              ->addIndex('restaurant_id')
              ->addIndex('restaurant_supervisor_id')
              ->addForeignKey('order_id', 'orders', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_id', 'restaurants', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_branch_id', 'restaurants', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_delivery_person_id', 'restaurant_delivery_persons', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('restaurant_delivery_person_orders');
    }
}
