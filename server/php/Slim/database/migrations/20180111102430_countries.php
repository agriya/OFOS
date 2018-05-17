<?php

use \Db\Migration\Migration;

class Countries extends Migration
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
        $countries = $this->table('countries');
        $countries->addColumn('created_at', 'timestamp',['null' => true])
              ->addColumn('updated_at', 'timestamp',['null' => true])        
              ->addColumn('name', 'string',['limit' => 255,'null' => true])
              ->addColumn('iso2', 'string',['limit' => 2,'null' => true])
              ->addColumn('iso3', 'string',['limit' => 3,'null' => true])
              ->addColumn('continent', 'string',['limit' => 2,'null' => true])
              ->addColumn('currency', 'string',['limit' => 3,'null' => true])
              ->addColumn('currencyname', 'string',['limit' => 20,'null' => true])
              ->addColumn('phone', 'string',['limit' => 10,'null' => true])
              ->addColumn('postalcodeformat', 'string',['limit' => 20,'null' => true])
              ->addColumn('postalcoderegex', 'string',['limit' => 20,'null' => true])
              ->addColumn('languages', 'string',['limit' => 200,'null' => true])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('countries');
    }
}
