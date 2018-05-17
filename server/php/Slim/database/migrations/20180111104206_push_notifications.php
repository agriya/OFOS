<?php

use \Db\Migration\Migration;

class PushNotifications extends Migration
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
        $push_notifications = $this->table('push_notifications');
        $push_notifications
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('user_device_id', 'biginteger')
              ->addColumn('message_type','string', ['limit' => 255])
              ->addColumn('message', 'text')
              ->addIndex('user_device_id')
              ->addForeignKey('user_device_id', 'device_details', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('push_notifications');
    }
}
