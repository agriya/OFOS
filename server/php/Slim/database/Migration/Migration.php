<?php

namespace Db\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration {
    /** @var \Illuminate\Database\Capsule\Manager $capsule */
    public $capsule;
    /** @var \Illuminate\Database\Schema\Builder $capsule */
    public $schema;

    public function init()  {
        $this->capsule = new Capsule;
        $this->capsule->addConnection([
          'driver'    => R_DB_DRIVER,
          'host'      => R_DB_HOST,
          'port'      => R_DB_PORT,
          'database'  => R_DB_NAME,
          'username'  => R_DB_USER,
          'password'  => R_DB_PASSWORD,
          'charset'   => 'utf8',
          'collation' => 'utf8_unicode_ci',
        ]);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
}