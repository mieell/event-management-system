<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;
    public string $defaultGroup = 'default';

    public array $default = [
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'event_management_system',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => true,
        'charset'  => 'utf8mb4',
        'DBCollat' => 'utf8mb4_unicode_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => true,
        'failover' => [],
        'port'     => 3306,
    ];

    public array $tests = [
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'event_management_test',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => true,
        'charset'  => 'utf8mb4',
        'DBCollat' => 'utf8mb4_unicode_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => true,
        'failover' => [],
        'port'     => 3306,
    ];

    public function __construct()
    {
        parent::__construct();

        $this->default['hostname'] = env('database.default.hostname', env('DB_HOST', $this->default['hostname']));
        $this->default['database'] = env('database.default.database', env('DB_DATABASE', $this->default['database']));
        $this->default['username'] = env('database.default.username', env('DB_USERNAME', $this->default['username']));
        $this->default['password'] = env('database.default.password', env('DB_PASSWORD', $this->default['password']));
        $this->default['port'] = (int) env('database.default.port', env('DB_PORT', $this->default['port']));

        $this->tests['hostname'] = env('database.tests.hostname', $this->tests['hostname']);
        $this->tests['database'] = env('database.tests.database', $this->tests['database']);
        $this->tests['username'] = env('database.tests.username', $this->tests['username']);
        $this->tests['password'] = env('database.tests.password', $this->tests['password']);
        $this->tests['port'] = (int) env('database.tests.port', $this->tests['port']);
    }
}
