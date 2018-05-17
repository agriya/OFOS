<?php

use \Db\Migration\Migration;

class UserTokens extends Migration
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
        $exists = $this->hasTable('user_tokens');
        if (!$exists) {
            $user_tokens = $this->table('user_tokens');
            $user_tokens
                    ->addColumn('created_at', 'timestamp')
                    ->addColumn('updated_at', 'timestamp')        
                    ->addColumn('user_id', 'biginteger')
                    ->addColumn('oauth_client_id', 'biginteger')
                    ->addColumn('token', 'string',['limit' => 255])
                    ->addColumn('expires', 'timestamp')
                    ->addIndex('user_id')
                    ->addIndex('oauth_client_id')
                    ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
                    ->addForeignKey('oauth_client_id', 'oauth_clients', 'id', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
                    ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('user_tokens');
        if ($exists) {
            $this->dropTable('user_tokens');
        }
    }
}
