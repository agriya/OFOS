<?php

use \Db\Migration\Migration;

class OauthClients extends Migration
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
        $oauth_clients = $this->table('oauth_clients');
        $oauth_clients->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('name', 'string',['limit' => 255])
              ->addColumn('api_key', 'string',['limit' => 80])
              ->addColumn('api_secret', 'string',['limit' => 80])
              ->addColumn('is_active', 'boolean',['default' => true])
              ->addIndex('api_key')
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('oauth_clients');
    }
}
