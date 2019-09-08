<?php

// Подключаем настройки конфигурации и автозагрузку классов
require 'config.php';
require 'Startup.php';

require 'Singleton.php';

use Core\Router;

$response = Core\Http\Response::getInstance();

$response->setHeader('Access-Control-Allow-Origin: *');
$response->setHeader('Access-Control-Allow-Methods: GET, POST');
$response->setHeader('Content-Type: application/json; charset=UTF-8');

try {
    $router = new Router();

    // Импорт файла с маршрутами
    require ROUTES.'Routes.php';

    $router->run();

} catch (Throwable $e) {
    $response->setContent(['error' => $e->getMessage()]);
}
// Отправляем ответ
$response->render();