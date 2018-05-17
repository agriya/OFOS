<?php

use \Db\Migration\Migration;

class SudopayTransactionLogs extends Migration
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
        $exists = $this->hasTable('sudopay_transaction_logs');
        if (!$exists) {
            $sudopay_transaction_logs = $this->table('sudopay_transaction_logs');
            $sudopay_transaction_logs
                    ->addColumn('created_at', 'timestamp')
                    ->addColumn('updated_at', 'timestamp')        
                    ->addColumn('class', 'string',['limit' => 50])
                    ->addColumn('foreign_id', 'biginteger')
                    ->addColumn('sudopay_pay_key', 'string',['limit' => 255])
                    ->addColumn('merchant_id', 'biginteger')              
                    ->addColumn('gateway_id', 'biginteger')              
                    ->addColumn('status', 'string', ['limit' => 50])              
                    ->addColumn('payment_type', 'string', ['limit' => 50])              
                    ->addColumn('buyer_id', 'biginteger')              
                    ->addColumn('buyer_email', 'string', ['limit' => 255])
                    ->addColumn('buyer_address', 'string', ['limit' => 255])
                    ->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => 0])
                    ->addColumn('payment_id','biginteger',['default' => 0])
                    ->addIndex('class')
                    ->addIndex('foreign_id')
                    ->addIndex('gateway_id')
                    ->addIndex('merchant_id')
                    ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('sudopay_transaction_logs');
        if ($exists) {
            $this->dropTable('sudopay_transaction_logs');
        }
    }
}
