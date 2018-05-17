<?php

use \Db\Migration\Migration;

class Orders extends Migration
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
        $orders = $this->table('orders');
        $orders->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('user_id', 'biginteger')
              ->addColumn('restaurant_id', 'biginteger')
              ->addColumn('restaurant_branch_id', 'biginteger',['null' => true])
              ->addColumn('restaurant_delivery_person_id', 'biginteger',['null' => true])
              ->addColumn('order_status_id', 'integer',['default' => 0])
              ->addColumn('payment_gateway_id', 'biginteger',['default' => 0])
              ->addColumn('gateway_id', 'biginteger',['default' => 0])
              ->addColumn('total_price', 'decimal',['precision' => 10, 'scale' => 2,'default' => 0])
              ->addColumn('delivery_charge', 'decimal',['precision' => 10, 'scale' => 2,'default' => 0])
              ->addColumn('sales_tax', 'decimal',['precision' => 10, 'scale' => 2,'default' => 0])
              ->addColumn('site_fee', 'decimal',['precision' => 10, 'scale' => 2,'default' => 0])
              ->addColumn('user_address_id', 'biginteger',['null' => true])
              ->addColumn('address', 'string',['limit' => 255])
              ->addColumn('city_id', 'biginteger',['null' => true])
              ->addColumn('state_id', 'biginteger',['null' => true])
              ->addColumn('country_id', 'biginteger',['null' => true])
              ->addColumn('latitude', 'decimal',['precision' => 10, 'scale' => 2,'default' => 0])
              ->addColumn('longitude', 'decimal',['precision' => 10, 'scale' => 2,'default' => 0])
              ->addColumn('zip_code', 'string',['limit' => 50, 'null' => true])
              ->addColumn('comment', 'text',['null' => true])
              ->addColumn('later_delivery_date', 'timestamp',['null' => true])
              ->addColumn('delivered_date', 'timestamp',['null' => true])
              ->addColumn('is_as_soon_as_delivery', 'boolean',['default' => false])
              ->addColumn('is_pickup_or_delivery', 'boolean',['default' => false])
              ->addColumn('success_url', 'string',['limit' => 255, 'null' => true])
              ->addColumn('cancel_url', 'string',['limit' => 255, 'null' => true])
              ->addColumn('paypal_pay_key', 'string',['limit' => 255, 'null' => true])
              ->addColumn('zazpay_pay_key', 'string',['limit' => 255, 'null' => true])
              ->addColumn('coupon_id', 'biginteger',[ 'null' => true])
              ->addColumn('discount_amount', 'decimal',['precision' => 10, 'scale' => 2,'default' => 0])
              ->addColumn('track_id', 'text',['null' => true])
              ->addIndex('city_id')
              ->addIndex('country_id')
              ->addIndex('order_status_id')
              ->addIndex('payment_gateway_id')
              ->addIndex('restaurant_branch_id')
              ->addIndex('restaurant_delivery_person_id')
              ->addIndex('restaurant_id')
              ->addIndex('state_id')
              ->addIndex('user_address_id')
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addForeignKey('restaurant_id', 'restaurants', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_branch_id', 'restaurants', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_delivery_person_id', 'restaurant_delivery_persons', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('order_status_id', 'order_statuses', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('payment_gateway_id', 'payment_gateways', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('user_address_id', 'user_addresses', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
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
        $this->dropTable('orders');
    }
}
