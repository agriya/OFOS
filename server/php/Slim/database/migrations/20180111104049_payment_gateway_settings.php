<?php

use \Db\Migration\Migration;

class PaymentGatewaySettings extends Migration
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
        $payment_gateway_settings = $this->table('payment_gateway_settings');
        $payment_gateway_settings->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('payment_gateway_id', 'integer')
              ->addColumn('name', 'string',['limit' => 255])
              ->addColumn('label', 'string',['limit' => 512])
              ->addColumn('description', 'text')
              ->addColumn('type', 'string',['null' => true, 'limit' => 8])
              ->addColumn('options', 'text')
              ->addColumn('test_mode_value', 'text', ['null' => true])
              ->addColumn('live_mode_value', 'text', ['null' => true])
              ->addIndex('payment_gateway_id')
              ->addForeignKey('payment_gateway_id', 'payment_gateways', 'id', ['delete'=> 'SET_NULL', 'update'=> 'SET_NULL'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('payment_gateway_settings');
    }
}
