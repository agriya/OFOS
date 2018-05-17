<?php

use \Db\Migration\Migration;
use Phinx\Db\Adapter\PostgresAdapter;
class Users extends Migration
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
        $exists = $this->hasTable('users');
        if (!$exists) {
            $users = $this->table('users');
            $users
                    ->addColumn('created_at', 'timestamp')
                    ->addColumn('updated_at', 'timestamp')        
                    ->addColumn('username', 'string',['limit' => 255])
                    ->addColumn('email', 'string',['limit' => 255])
                    ->addColumn('password', 'string',['limit' => 255])
                    ->addColumn('role_id', 'integer',['default' => 2])
                    ->addColumn('provider_id', 'biginteger',['default' => 0])
                    ->addColumn('first_name', 'string',['limit' => 150, 'null' => true])
                    ->addColumn('last_name', 'string',['limit' => 150, 'null' => true])
                    ->addColumn('gender_id', 'integer', ['limit' => PostgresAdapter::INT_SMALL,'null' => true])
                    ->addColumn('dob', 'date',['null' => true])
                    ->addColumn('about_me', 'text',['null' => true])
                    ->addColumn('address', 'string',['limit' => 255,'null' => true])
                    ->addColumn('address1', 'string',['limit' => 255,'null' => true])
                    ->addColumn('phone', 'string',['limit' => 20,'null' => true])
                    ->addColumn('mobile', 'string',['limit' => 15,'null' => true])
                    ->addColumn('city_id', 'biginteger',['default' => 0])
                    ->addColumn('state_id', 'biginteger',['default' => 0])
                    ->addColumn('country_id', 'biginteger',['default' => 0])
                    ->addColumn('latitude', 'decimal',['precision' => 10, 'scale' => 6, 'null' => true])
                    ->addColumn('longitude', 'decimal',['precision' => 10, 'scale' => 6, 'null' => true])
                    ->addColumn('available_wallet_amount', 'decimal',['precision' => 10, 'scale' => 2, 'default' => 0])
                    ->addColumn('total_orders', 'biginteger',['default' => 0])
                    ->addColumn('total_reviews', 'biginteger',['default' => 0])
                    ->addColumn('zip_code', 'string',['limit' => 50, 'null' => true])
                    ->addColumn('last_logged_in_time', 'timestamp',['null' => true])
                    ->addColumn('last_login_ip_id', 'string',['limit' => 30, 'default' => 0])
                    ->addColumn('is_email_confirmed', 'boolean',['default' => false])
                    ->addColumn('is_agree_terms_conditions', 'boolean',['default' => false])
                    ->addColumn('is_subscribed', 'boolean',['default' => false])
                    ->addColumn('is_active', 'boolean',['default' => false])
                    ->addColumn('is_created_from_order_page', 'boolean',['default' => false])
                    ->addColumn('mobile_code', 'string',['limit' => 255, 'null' => true])
                    ->addIndex('city_id')
                    ->addIndex('country_id')
                    ->addIndex('email')
                    ->addIndex('gender_id')
                    ->addIndex('last_login_ip_id')
                    ->addIndex('provider_id')
                    ->addIndex('role_id')
                    ->addIndex('state_id')
                    ->addIndex('username')
                    ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('users');
        if ($exists) {
            $this->dropTable('users');
        }
    }
}
