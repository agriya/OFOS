<?php

use Phinx\Seed\AbstractSeed;

class TransactionTypesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Amount added to wallet',
                'is_credit' => 1,
                'is_credit_to_other_user' => 0,
                'is_credit_to_admin' => 0,
                'message' => 'Amount added to wallet',
                'message_for_other_user' => NULL,
                'message_for_admin' => '##USER## added amount to own wallet',
                'transaction_variables' => 'USER'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Order placed',
                'is_credit' => 0,
                'is_credit_to_other_user' => 0,
                'is_credit_to_admin' => 1,
                'message' => 'Order placed ###ORDER_ID##',
                'message_for_other_user' => '##USER## placed an order ###ORDER_ID##',
                'message_for_admin' => '##USER## placed an order ###ORDER_ID##',
                'transaction_variables' => 'USER, ORDER_ID'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Refund for rejected order',
                'is_credit' => 0,
                'is_credit_to_other_user' => 1,
                'is_credit_to_admin' => 0,
                'message' => '##RESTAURANT## rejected order ###ORDER_ID##',
                'message_for_other_user' => 'You have rejected order ###ORDER_ID##',
                'message_for_admin' => '##RESTAURANT## rejected order ###ORDER_ID##',
                'transaction_variables' => 'RESTAURANT, ORDER_ID'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Paid amount to restaurant',
                'is_credit' => 0,
                'is_credit_to_other_user' => 1,
                'is_credit_to_admin' => 0,
                'message' => '###ORDER_ID## amount paid',
                'message_for_other_user' => '###ORDER_ID## amount paid to ##RESTAURANT##',
                'message_for_admin' => '###ORDER_ID## amount paid to ##RESTAURANT##',
                'transaction_variables' => 'ORDER_ID, RESTAURANT'
            ]                                    
        ];

        $transaction_types = $this->table('transaction_types');
        $transaction_types->insert($data)
              ->save();
    }
}
