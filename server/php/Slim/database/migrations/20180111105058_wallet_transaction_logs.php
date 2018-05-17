<?php

use \Db\Migration\Migration;

class WalletTransactionLogs extends Migration
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
        $exists = $this->hasTable('wallet_transaction_logs');
        if (!$exists) {
            $wallet_transaction_logs = $this->table('wallet_transaction_logs');
            $wallet_transaction_logs
                    ->addColumn('created_at', 'timestamp')
                    ->addColumn('updated_at', 'timestamp')        
                    ->addColumn('amount', 'decimal',['precision' => 10, 'scale' => 2, 'default' => '0.00'])
                    ->addColumn('foreign_id', 'biginteger')
                    ->addColumn('class', 'string',['limit' => 255])
                    ->addColumn('status', 'string',['limit' => 255])
                    ->addColumn('payment_type', 'string',['limit' => 255])
                    ->addIndex('foreign_id')
                    ->addIndex('class')
                    ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('wallet_transaction_logs');
        if ($exists) {
            $this->dropTable('wallet_transaction_logs');
        }
    }
}
