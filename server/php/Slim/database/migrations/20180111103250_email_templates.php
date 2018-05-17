<?php

use \Db\Migration\Migration;

class EmailTemplates extends Migration
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
        $email_templates = $this->table('email_templates');
        $email_templates->addColumn('created_at', 'timestamp')
              ->addColumn('updated_at', 'timestamp')        
              ->addColumn('name', 'string',['limit' => 255])
              ->addColumn('description', 'text')
              ->addColumn('from_email', 'string',['limit' => 255])
              ->addColumn('reply_to_email', 'string',['limit' => 255])
              ->addColumn('subject', 'string')
              ->addColumn('email_variables', 'string',['limit' => 500])
              ->addColumn('html_email_content', 'text', ['null' => true])
              ->addColumn('text_email_content', 'text', ['null' => true])
              ->addColumn('display_name', 'string',['null' => true,'limit' => 255])
              ->addColumn('to_email', 'text',['default' => '##TO_EMAIL##'])
              ->addColumn('is_admin_email', 'boolean',['default' => false])
              ->addColumn('plugin', 'string',['null' => true,'limit' => 255])
              ->addColumn('is_html', 'boolean',['default' => false]) 
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('email_templates');
    }
}
