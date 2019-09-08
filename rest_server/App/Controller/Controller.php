<?php

namespace App\Controller;

use Core\Http\Request;
use Core\Http\Response;

/**
 * Базовый контроллер
 */
class Controller
{
    /**
     * @var Core\Http\Request
     */
    public $request;

    /**
     * @var Core\Http\Response
     */
    public $response;

    /**
     * Инициализация переменных для возможности доступа к классам запросов и ответов
     */
    public function __construct() {
        $this->request = Request::getInstance();
        $this->response = Response::getInstance();
    }
}
