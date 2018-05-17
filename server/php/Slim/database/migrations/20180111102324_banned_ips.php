<?php

use \Db\Migration\Migration;

class BannedIps extends Migration
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
        $banned_ips = $this->table('banned_ips');
        $banned_ips->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('address', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('range', 'text',['null' => true])
              ->addColumn('reason', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('redirect', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('thetime', 'integer')
              ->addColumn('timespan', 'integer')
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('banned_ips');
    }
}
