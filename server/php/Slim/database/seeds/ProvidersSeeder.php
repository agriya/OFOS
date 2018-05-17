<?php

use Phinx\Seed\AbstractSeed;

class ProvidersSeeder extends AbstractSeed
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
        $data = array(
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'Facebook',
                'slug' => 'facebook',
                'secret_key' => '703f1ba7d1e37c730fc78133eb356bc2',
                'api_key' => 192562234629073,
                'icon_class' => 'fa-facebook',
                'button_class' => 'btn-facebook',
                'display_order' => 1,
                'is_active' => 0,
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'Google',
                'slug' => 'google',
                'secret_key' => 'Y4uK6bviyBB8HE41w-tnuhIt',
                'api_key' => '1049343239400-sbna4or6cns522qiunb0bon6mip6c2mv.apps.googleusercontent.com',
                'icon_class' => 'fa-google-plus',
                'button_class' => 'btn-google',
                'display_order' => 3,
                'is_active' => 1,
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'Twitter',
                'slug' => 'twitter',
                'secret_key' => 'r7k6KTkfW2xu6mDwgNmohVh5QLoEG36YbIpB6n2vwr2y1cSFvA',
                'api_key' => 'tELI7PJYUN788gOidXCTeYgQ3',
                'icon_class' => 'fa-twitter',
                'button_class' => 'btn-twitter',
                'display_order' => 2,
                'is_active' => 1,
            ) ,
        );
        $posts = $this->table('providers');
        $posts->insert($data)
              ->save();
    }
}
