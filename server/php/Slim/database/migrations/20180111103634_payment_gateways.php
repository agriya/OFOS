<?php

use \Db\Migration\Migration;

class PaymentGateways extends Migration
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
        $payment_gateways = $this->table('payment_gateways');
        $payment_gateways->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('name', 'string',['limit' => 255])
              ->addColumn('display_name', 'string',['limit' => 255])
              ->addColumn('description', 'text')
              ->addColumn('gateway_fees', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => true])
              ->addColumn('is_test_mode', 'boolean',['default' => true])
              ->addColumn('is_active', 'boolean',['default' => true])
              ->addColumn('is_enable_for_wallet', 'boolean', ['default' => false])
              ->addColumn('plugin', 'string', ['limit' => 255, 'null' => true])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('payment_gateways');
    }
}
