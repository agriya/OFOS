<?php

use \Db\Migration\Migration;
use Phinx\Db\Adapter\PostgresAdapter;
class Wallets extends Migration
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
        $exists = $this->hasTable('wallets');
        if (!$exists) {
            $wallets = $this->table('wallets');
            $wallets
                    ->addColumn('created_at', 'timestamp')
                    ->addColumn('updated_at', 'timestamp')        
                    ->addColumn('user_id', 'biginteger')
                    ->addColumn('amount', 'decimal',['precision' => 10, 'scale' => 2])
                    ->addColumn('payment_gateway_id', 'integer', ['limit' => PostgresAdapter::INT_SMALL,'default' => 0])
                    ->addColumn('gateway_id', 'biginteger',['default' => 0])
                    ->addColumn('is_payment_completed', 'boolean',['default' => false])
                    ->addColumn('success_url', 'string',['null' => true])
                    ->addColumn('cancel_url', 'string',['null' => true])
                    ->addColumn('paypal_pay_key', 'string',['limit' => 255,'null' => true])
                    ->addColumn('zazpay_pay_key', 'string',['limit' => 255,'null' => true])
                    ->addIndex('user_id')
                    ->addIndex('payment_gateway_id')
                    ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('wallets');
        if ($exists) {
            $this->dropTable('wallets');
        }
    }
}
