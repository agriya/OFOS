<?php

use \Db\Migration\Migration;

class RestaurantDeliveryPersons extends Migration
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
        $restaurant_delivery_persons = $this->table('restaurant_delivery_persons');
        $restaurant_delivery_persons
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('user_id', 'biginteger')
              ->addColumn('restaurant_id', 'biginteger',['null' => true])
              ->addColumn('restaurant_branch_id', 'biginteger',['null' => true])
              ->addColumn('restaurant_supervisor_id', 'biginteger',['null' => true])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addIndex('restaurant_branch_id')
              ->addIndex('restaurant_id')
              ->addIndex('restaurant_supervisor_id')
              ->addIndex('user_id')
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addForeignKey('restaurant_id', 'restaurants', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_branch_id', 'restaurants', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_supervisor_id', 'restaurant_supervisors', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('restaurant_delivery_persons');
    }
}
