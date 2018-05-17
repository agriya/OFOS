<?php

use \Db\Migration\Migration;

class ProviderUsers extends Migration
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
        $provider_users = $this->table('provider_users');
        $provider_users->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('user_id', 'biginteger')
              ->addColumn('provider_id', 'biginteger')
              ->addColumn('foreign_id', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('profile_picture_url', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('access_token', 'string', ['limit' => 255])
              ->addColumn('access_token_secret', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('is_connected', 'boolean', ['default' => true])
              ->addIndex('foreign_id')
              ->addIndex('provider_id')
              ->addIndex('user_id')
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addForeignKey('provider_id', 'providers', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('provider_users');
    }
}
