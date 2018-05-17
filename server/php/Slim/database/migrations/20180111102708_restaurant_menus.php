<?php

use \Db\Migration\Migration;

class RestaurantMenus extends Migration
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
        $restaurant_menus = $this->table('restaurant_menus');
        $restaurant_menus
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('cuisine_id', 'biginteger',['null' => true])
              ->addColumn('restaurant_id', 'biginteger')
              ->addColumn('restaurant_category_id', 'biginteger')
              ->addColumn('menu_type_id', 'integer',['limit' => 1,'default' => 1,'comment' => '1- Veg, 2- Non-Veg'])
              ->addColumn('name', 'string', ['limit' => 255])
              ->addColumn('description', 'text',['null' => true])
              ->addColumn('display_order', 'integer', ['default' => 0])
              ->addColumn('is_addon', 'boolean', ['default' => false])
              ->addColumn('is_popular', 'boolean', ['default' => false])
              ->addColumn('is_spicy', 'boolean', ['default' => false])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addColumn('color', 'string',['limit' => 255, 'null' => true])
              ->addColumn('stock', 'biginteger',['default' => 0])
              ->addColumn('sold_quantity', 'biginteger',['default' => 0])
              ->addColumn('slug', 'string',['limit' => 255, 'null' => true])
              ->addColumn('ordered_menu_count', 'biginteger',['default' => 0])
              ->addIndex('cuisine_id')
              ->addIndex('restaurant_category_id')
              ->addIndex('restaurant_id')
              ->addIndex('menu_type_id')
              ->addForeignKey('cuisine_id', 'cuisines', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_id', 'restaurants', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('restaurant_category_id', 'restaurant_categories', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('restaurant_menus');
    }
}
