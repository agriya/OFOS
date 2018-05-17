<?php

use \Db\Migration\Migration;

class Contacts extends Migration
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
        $contacts = $this->table('contacts');
        $contacts->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('first_name', 'string',['limit' => 255])
              ->addColumn('last_name', 'string',['limit' => 255])
              ->addColumn('email', 'string',['limit' => 255])
              ->addColumn('phone', 'string',['limit' => 50])
              ->addColumn('subject', 'string',['limit' => 255])
              ->addColumn('message', 'text')
              ->addColumn('ip_id', 'biginteger',['null' => true])
              ->addForeignKey('ip_id', 'ips', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('contacts');
    }
}
