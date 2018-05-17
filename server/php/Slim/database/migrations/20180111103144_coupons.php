<?php

use \Db\Migration\Migration;

class Coupons extends Migration
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
        $coupons = $this->table('coupons');
        $coupons->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('user_id', 'biginteger')
              ->addColumn('restaurant_id', 'biginteger')
              ->addColumn('coupon_code', 'string',['limit' => 255])
              ->addColumn('discount', 'decimal',['precision' => 10,'scale' => 2, 'default'=>0])
              ->addColumn('is_flat_discount_in_amount', 'boolean',['default' => true])
              ->addColumn('no_of_quantity_allowed', 'biginteger',['null' => true])
              ->addColumn('no_of_quantity_used', 'biginteger',['default' => 0])
              ->addColumn('validity_start_date', 'date',['null' => true])
              ->addColumn('validity_end_date', 'date',['null' => true])
              ->addColumn('maximum_discount_amount', 'decimal',['precision' => 10,'scale' => 2, 'default'=>0])
              ->addColumn('is_active', 'boolean',['default' => true])
              ->addIndex('restaurant_id')
              ->addIndex('user_id')
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])   
              ->addForeignKey('restaurant_id', 'restaurants', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])    
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('coupons');
    }
}
