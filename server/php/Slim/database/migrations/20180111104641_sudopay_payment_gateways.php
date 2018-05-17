<?php

use \Db\Migration\Migration;

class SudopayPaymentGateways extends Migration
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
        $sudopay_payment_gateways = $this->table('sudopay_payment_gateways');
        $sudopay_payment_gateways
              ->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('sudopay_gateway_name', 'string',['limit' => 255])
              ->addColumn('sudopay_gateway_id', 'biginteger')
              ->addColumn('sudopay_payment_group_id', 'biginteger')
              ->addColumn('sudopay_gateway_details', 'text')
              ->addColumn('is_marketplace_supported', 'boolean')
              ->addIndex('sudopay_payment_group_id')
              ->addIndex('sudopay_gateway_id')
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('sudopay_payment_gateways');
    }
}
