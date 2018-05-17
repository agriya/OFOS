<?php

use \Db\Migration\Migration;

class DeviceDetails extends Migration
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
        $device_details = $this->table('device_details');
        $device_details->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('user_id', 'biginteger',['null' => true])
              ->addColumn('appname', 'string',['null' => true,'limit' => 255])
              ->addColumn('appversion', 'string',['null' => true,'limit' => 255])
              ->addColumn('deviceuid', 'text',['null' => true])
              ->addColumn('devicetoken', 'text',['null' => true])
              ->addColumn('devicename', 'string',['null' => true,'limit' => 255])
              ->addColumn('devicemodel', 'string',['null' => true,'limit' => 255])
              ->addColumn('deviceversion', 'string',['null' => true,'limit' => 255])
              ->addColumn('pushbadge', 'string',['null' => true,'limit' => 255])
              ->addColumn('pushalert', 'string',['null' => true,'limit' => 255])
              ->addColumn('pushsound', 'string',['null' => true,'limit' => 255])
              ->addColumn('development', 'string',['null' => true,'limit' => 255])
              ->addColumn('status', 'string',['null' => true,'limit' => 255])
              ->addColumn('latitude', 'decimal',['precision' => 10,'scale' => 8])
              ->addColumn('longitude', 'decimal',['precision' => 10,'scale' => 8])
              ->addColumn('devicetype','integer',['comment'=>'1. Android 2. iPhone'])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION']) 
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('device_details');
    }
}
