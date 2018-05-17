<?php

use \Db\Migration\Migration;

class RestaurantAddons extends Migration
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
        $restaurant_addons = $this->table('restaurant_addons');
        $restaurant_addons
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('restaurant_id', 'biginteger')
              ->addColumn('restaurant_category_id', 'biginteger')
              ->addColumn('name','string', ['limit' => 255])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addColumn('is_multiple', 'boolean', ['default' => true])
              ->addIndex('restaurant_id')
              ->addIndex('restaurant_category_id')
              ->addForeignKey('restaurant_id', 'restaurants', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_category_id', 'restaurant_categories', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('restaurant_addons');
    }
}
