<?php

use \Db\Migration\Migration;

class UserAddresses extends Migration
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
        $exists = $this->hasTable('user_addresses');
        if (!$exists) {
            $user_addresses = $this->table('user_addresses');
            $user_addresses
                    ->addColumn('created_at', 'timestamp')
                    ->addColumn('updated_at', 'timestamp')        
                    ->addColumn('user_id', 'biginteger')
                    ->addColumn('title', 'string',['limit' => 255])
                    ->addColumn('building_address', 'string',['limit' => 255, 'null' => true])
                    ->addColumn('address2', 'string',['limit' => 255,'null' => true])
                    ->addColumn('landmark', 'string',['limit' => 255, 'null' => true])
                    ->addColumn('city_id', 'biginteger')
                    ->addColumn('state_id', 'biginteger')
                    ->addColumn('country_id', 'biginteger')
                    ->addColumn('zip_code', 'string',['limit' => 30, 'null' => true])
                    ->addColumn('latitude', 'decimal',['precision' => 10, 'scale' => 6])
                    ->addColumn('longitude', 'decimal',['precision' => 10, 'scale' => 6])
                    ->addColumn('hash', 'string',['limit' => 255, 'null' => true])
                    ->addColumn('is_active', 'boolean',['default' => true])
                    ->addIndex('city_id')
                    ->addIndex('country_id')
                    ->addIndex('state_id')
                    ->addIndex('user_id')
                    ->save();
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('user_addresses');
        if ($exists) {
            $this->dropTable('user_addresses');
        }
    }
}
