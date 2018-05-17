<?php

use \Db\Migration\Migration;

class Cities extends Migration
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
        $cities = $this->table('cities');
        $cities->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('country_id', 'biginteger',['default' => 0])
              ->addColumn('state_id', 'biginteger',['default' => 0])
              ->addColumn('name', 'string',['limit' => 255])
              ->addColumn('is_active', 'boolean',['default' => true])
              ->addIndex('state_id')
              ->addIndex('country_id')
              ->addForeignKey('country_id', 'countries', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->addForeignKey('state_id', 'states', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('cities');
    }
}
