<?php

use \Db\Migration\Migration;

class Transactions extends Migration
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
        $exists = $this->hasTable('transactions');
        if (!$exists) {
            $transactions = $this->table('transactions');
            $transactions
                    ->addColumn('created_at', 'timestamp')
                    ->addColumn('updated_at', 'timestamp')        
                    ->addColumn('user_id', 'biginteger')
                    ->addColumn('other_user_id', 'biginteger',['default' => 0])
                    ->addColumn('restaurant_id', 'biginteger',['default' => 0])
                    ->addColumn('amount', 'decimal',['precision' => 10, 'scale' => 2])
                    ->addColumn('foreign_id', 'biginteger')
                    ->addColumn('class', 'string',['limit' => 255])
                    ->addColumn('transaction_type_id', 'integer')
                    ->addColumn('payment_gateway_id', 'integer',['null' => true])
                    ->addColumn('gateway_fees', 'decimal',['precision' => 10, 'scale' => 2, 'null' => true])
                    ->addIndex('class')
                    ->addIndex('foreign_id')
                    ->addIndex('other_user_id')
                    ->addIndex('payment_gateway_id')
                    ->addIndex('transaction_type_id')
                    ->addIndex('user_id')
                    ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('transactions');
        if ($exists) {
            $this->dropTable('transactions');
        }
    }
}
