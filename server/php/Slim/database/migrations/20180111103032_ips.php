<?php

use \Db\Migration\Migration;

class Ips extends Migration
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
        $ips = $this->table('ips');
        $ips->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('ip', 'string',['limit' => 255])
              ->addColumn('host', 'string',['limit' => 255])
              ->addColumn('city_id', 'biginteger',['null' => true])
              ->addColumn('state_id', 'biginteger',['null' => true])
              ->addColumn('country_id', 'biginteger',['null' => true])
              ->addColumn('latitude', 'decimal',['precision' => 10, 'scale' => 8,'null' => true])
              ->addColumn('longitude', 'decimal',['precision' => 10, 'scale' => 8,'null' => true])
              ->addIndex('city_id')
              ->addIndex('country_id')
              ->addIndex('state_id')
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
        $this->dropTable('ips');
    }
}
