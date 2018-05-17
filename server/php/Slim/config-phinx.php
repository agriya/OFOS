<?php
require __DIR__.'/../config.inc.php';
return [
  'paths' => [
    'migrations' => 'database/migrations'
  ],
	
  'migration_base_class' => '\Db\Migration\Migration',
  'environments' => [
    'default_migration_table' => 'phinxlog',
    'default_database' => 'dev',
    'dev' => [
      'adapter' => R_DB_DRIVER,
      'host' => R_DB_HOST,
      'name' => R_DB_NAME,
      'user' => R_DB_USER,
      'pass' => R_DB_PASSWORD,
      'port' => R_DB_PORT
    ]
  ]
];