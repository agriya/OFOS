<?php

use Phinx\Seed\AbstractSeed;

class PaymentGatewaysSeeder extends AbstractSeed
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

        $data = array
        (
            0 => array
                (
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'name' => 'ZazPay',
                    'display_name' => 'ZazPay',
                    'description' => 'ZazPay payment',
                    'gateway_fees' => 0,
                    'is_test_mode' => 1,
                    'is_active' => 1,
                    'is_enable_for_wallet' => 0,
                    'plugin' => 'Common/ZazPay'
                ),

            1 => array
                (
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'name' => 'Wallet',
                    'display_name' => 'Wallet',
                    'description' => 'Wallet payment' ,
                    'gateway_fees' => 0,
                    'is_test_mode' => 1,
                    'is_active' => 1,
                    'is_enable_for_wallet' => 1,
                    'plugin' => 'Common/Wallet',
                ),

            2 => array
                (
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'name' => 'COD',
                    'display_name' => 'Cash On Delivery',
                    'description' => 'Cash On Delivery',
                    'is_test_mode' => 1,
                    'is_active' => 1,
                    'is_enable_for_wallet' => 0,
                    'plugin' => 'Common/COD'
                ),

            3 => array
                (
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'name' => 'Paypal',
                    'display_name' => 'paypal',
                    'description' => 'Payment through PayPal',
                    'gateway_fees' => 0,
                    'is_test_mode' => 1,
                    'is_active' => 1,
                    'is_enable_for_wallet' =>0,
                    'plugin' => 'Common/Paypal',
                )

        );


       /* $data = [
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'ZazPay',
                'email' => 'productdemo.admin@gmail.com',
                'password' => '$2y$12$7Bezs1GQsctRnC80lGMC7e4Q.g2opvnIyURlXhFqQ7urzI1voVp5y',
                'role_id' => 1,
                'mobile' => 123456789,
                'is_email_confirmed' => 1,
                'is_agree_terms_conditions' => false,
                'is_subscribed' => 1,
                'is_active' => 1,
                'is_created_from_order_page' => false
            ]
        ];*/

        $payment_gateways = $this->table('payment_gateways');
        $payment_gateways->insert($data)
              ->save();
    }
}
