<?php

use \Db\Migration\Migration;

class Restaurants extends Migration
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
        $restaurants = $this->table('restaurants');
        $restaurants
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('user_id', 'biginteger')
              ->addColumn('parent_id', 'biginteger',['null' => true])
              ->addColumn('name', 'string', ['limit' => 255])
              ->addColumn('slug', 'string', ['limit' => 255])
              ->addColumn('phone', 'string', ['limit' => 20, 'null' => true])
              ->addColumn('mobile', 'string', ['limit' => 15])
              ->addColumn('fax', 'string', ['limit' => 20,'null' => true])
              ->addColumn('contact_name', 'string', ['limit' => 150])
              ->addColumn('contact_phone', 'string', ['limit' => 20, 'null' => true])
              ->addColumn('website', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('address', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('address1', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('city_id', 'biginteger')
              ->addColumn('state_id', 'biginteger')
              ->addColumn('country_id', 'biginteger')
              ->addColumn('latitude', 'decimal',['precision' => 10, 'scale' => 8, 'null' => true])
              ->addColumn('longitude', 'decimal',['precision' => 10, 'scale' => 8, 'null' => true])
              ->addColumn('hash', 'text',['null' => true])
              ->addColumn('zip_code', 'string',['limit' => 30, 'null' => true])
              ->addColumn('sales_tax', 'decimal',['precision' => 10, 'scale' => 2, 'default' => 0])
              ->addColumn('minimum_order_for_booking', 'decimal',['precision' => 10, 'scale' => 2, 'default' => 0])
              ->addColumn('estimated_time_to_delivery', 'integer')
              ->addColumn('delivery_charge', 'decimal',['precision' => 10, 'scale' => 2])
              ->addColumn('delivery_miles', 'integer')
              ->addColumn('total_reviews', 'biginteger',['default' => 0])
              ->addColumn('avg_rating', 'decimal',['precision' => 10, 'scale' => 2, 'default' => 0])
              ->addColumn('total_orders', 'biginteger',['default' => 0])
              ->addColumn('total_revenue', 'decimal',['precision' => 10, 'scale' => 2, 'default' => 0])
              ->addColumn('is_allow_users_to_door_delivery_order', 'boolean',['default' => false])
              ->addColumn('is_allow_users_to_pickup_order', 'boolean',['default' => false])
              ->addColumn('is_allow_users_to_preorder', 'boolean',['default' => true])
              ->addColumn('is_active', 'boolean',['default' => true])
              ->addColumn('is_closed', 'boolean',['default' => true])
              ->addColumn('is_delivered_by_own', 'boolean',['default' => false])
              ->addColumn('mobile_code', 'string',['limit' => 255, 'null' => true])
              ->addIndex('city_id')
              ->addIndex('country_id')
              ->addIndex('parent_id')
              ->addIndex('slug')
              ->addIndex('state_id')
              ->addIndex('user_id')
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('parent_id', 'restaurants', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('city_id', 'cities', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('state_id', 'states', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('country_id', 'countries', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('restaurants');
    }
}
