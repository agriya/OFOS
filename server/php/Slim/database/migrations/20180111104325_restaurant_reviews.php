<?php

use \Db\Migration\Migration;

class RestaurantReviews extends Migration
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
        $restaurant_reviews = $this->table('restaurant_reviews');
        $restaurant_reviews
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('user_id', 'biginteger')
              ->addColumn('order_id', 'biginteger')
              ->addColumn('restaurant_id', 'biginteger')
              ->addColumn('rating', 'integer')
              ->addColumn('message', 'text')
              ->addColumn('is_active', 'boolean',['default' => true])
              ->addIndex('order_id')
              ->addIndex('restaurant_id')
              ->addIndex('user_id')
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addForeignKey('order_id', 'orders', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_id', 'restaurants', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('restaurant_reviews');
    }
}
