<?php

use \Db\Migration\Migration;

class UserAddWalletAmounts extends Migration
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
        $exists = $this->hasTable('user_add_wallet_amounts');
        if (!$exists) {
            $user_add_wallet_amounts = $this->table('user_add_wallet_amounts');
            $user_add_wallet_amounts
                    ->addColumn('created_at', 'timestamp')
                    ->addColumn('updated_at', 'timestamp')        
                    ->addColumn('user_id', 'biginteger')
                    ->addColumn('description', 'text',['null' => true])
                    ->addColumn('amount', 'decimal',['precision' => 10, 'scale' => 2,'default' => '0.00'])
                    ->addColumn('payment_gateway_id', 'biginteger',['null' => true])
                    ->addColumn('sudopay_gateway_id', 'biginteger',['null' => true])
                    ->addColumn('sudopay_revised_amount', 'decimal',['precision' => 10, 'scale' => 2,'default' => '0.00'])
                    ->addColumn('sudopay_token', 'string', ['limit' => 255, 'null' => true])
                    ->addColumn('is_success', 'boolean',['default' => false])
                    ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('user_add_wallet_amounts');
        if ($exists) {
            $this->dropTable('user_add_wallet_amounts');
        }
    }
}
