<?php

use Phinx\Seed\AbstractSeed;

class OauthClientsSeeder extends AbstractSeed
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
                'name' => 'Web',
                'api_key' => '4542632501382585',
                'api_secret' => '3f7C4l1Y2b0S6a7L8c1E7B3Jo3'
            ]
        ];

        $roles = $this->table('oauth_clients');
        $roles->insert($data)
              ->save();
    }
}
