<?php

use \Db\Migration\Migration;

class TransactionTypes extends Migration
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
        $exists = $this->hasTable('transaction_types');
        if (!$exists) {
            $transaction_types = $this->table('transaction_types');
            $transaction_types
                    ->addColumn('created_at', 'timestamp')
                    ->addColumn('updated_at', 'timestamp')        
                    ->addColumn('name', 'string',['limit' => 255])
                    ->addColumn('is_credit', 'boolean')
                    ->addColumn('is_credit_to_other_user', 'boolean')
                    ->addColumn('is_credit_to_admin', 'boolean')
                    ->addColumn('message', 'string',['limit' => 255,'null' => true])
                    ->addColumn('message_for_other_user', 'string',['limit' => 255,'null' => true])
                    ->addColumn('message_for_admin', 'string',['limit' => 255,'null' => true])
                    ->addColumn('transaction_variables', 'text',['null' => true])
                    ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('transaction_types');
        if ($exists) {
            $this->dropTable('transaction_types');
        }
    }
}
