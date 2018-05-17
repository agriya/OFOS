<?php

use \Db\Migration\Migration;

class UserCashWithdrawals extends Migration
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
        $exists = $this->hasTable('user_cash_withdrawals');
        if (!$exists) {
            $user_cash_withdrawals = $this->table('user_cash_withdrawals');
            $user_cash_withdrawals
                    ->addColumn('created_at', 'timestamp')
                    ->addColumn('updated_at', 'timestamp')        
                    ->addColumn('user_id', 'biginteger')
                    ->addColumn('money_transfer_account_id', 'biginteger')
                    ->addColumn('amount', 'decimal',['precision' => 10, 'scale' => 2])
                    ->addColumn('remark', 'text',['null' => true])
                    ->addColumn('status', 'integer',['default' => 0])
                    ->addIndex('user_id')
                    ->addIndex('money_transfer_account_id')
                    ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('user_cash_withdrawals');
        if ($exists) {
            $this->dropTable('user_cash_withdrawals');
        }
    }
}
