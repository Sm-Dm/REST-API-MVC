<?php
define('CORE', str_replace('\\', '/', rtrim(realpath(__DIR__), '/')) . '/');
define('ROOT', rtrim(realpath(CORE . '..' . '/'), '/') . '/');

define('NAMESPACE_CONTROLLER', 'App\Controller');
define('ROUTES', ROOT . 'Routes/');

// Параметры настройки БД
define('CONFIG_DB', [
    'Port'      => '3307',
    'Host'      => 'localhost',
    'Db'        => 'test',
    'User'      => 'root',
    'Password'  => '123'
]);