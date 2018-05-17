<?php

use Phinx\Seed\AbstractSeed;

class SettingCategoriesSeeder extends AbstractSeed
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
                'name' => 'System',
                'description' => 'Manage site name, contact email, from email and reply to email.',
                'plugin' => null,
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'SEO',
                'description' => 'Manage content, meta data and other information relevant to browsers or search engines.',
                'plugin' => null,
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'Regional, Currency & Language',
                'description' => 'Manage site default language, currency and date-time format.',
                'plugin' => null,
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'Account',
                'description' => 'Manage user account related settings',
                'plugin' => null,
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'Third Party API',
                'description' => 'Manage third party API related settings',
                'plugin' => null,
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'Wallet',
                'description' => 'Manage wallet related settings.',
                'plugin' => 'Common/Wallet',
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'Withdrawals',
                'description' => 'Manage withdrawal related settings.',
                'plugin' => 'Common/Withdrawal',
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'Widget',
                'description' => 'Widgets for header, footer, view page. Widgets can be in iframe and JavaScript embed code, etc (e.g., Twitter Widget, Facebook Like Box, Facebook Feeds Code, Google Ads).',
                'plugin' => 'Restaurant/MultiRestaurant',
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'Revenue',
                'description' => 'Manage revenue related settings',
                'plugin' => 'Order/Order',
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'Mobile',
                'description' => 'Here you can manage Mobile related settings.',
                'plugin' => 'Order/Mobile',
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'SMS',
                'description' => 'Manage SMS Related settingd here.',
                'plugin' => 'Order/Sms',
            ) ,
            array(
                'created_at' => date('Y-m-d H:i:s') ,
                'updated_at' => date('Y-m-d H:i:s') ,
                'name' => 'Order',
                'description' => 'We can manage Order related settings here.',
                'plugin' => 'Order/Order',
            ) ,
        );
        $setting_categories = $this->table('setting_categories');
        $setting_categories->insert($data)
              ->save();
    }
}
