<?php
require_once __DIR__ . '/../../lib/core.php';
use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
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
                'username' => 'admin',
                'email' => 'productdemo.admin@gmail.com',
                'password' => getCryptHash('agriya'),
                'role_id' => 1,
                'mobile' => 123456789,
                'is_email_confirmed' => 1,
                'is_agree_terms_conditions' => 0,
                'is_subscribed' => 1,
                'is_active' => 1,
                'is_created_from_order_page' => 0
            ]
        ];

        $roles = $this->table('users');
        $roles->insert($data)
              ->save();
    }
}
