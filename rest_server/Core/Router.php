<?php

namespace Core;

use Core\Http\Response;
use Core\Http\Request;

/**
 * Маршрутизатор
 */
class Router {

    /**
     * @var array Массив объектов Route
     */
    public $router = [];

    /**
     * @var array Совпадающий с запросом маршрут
     */
    private $matchRouter;

    /**
     * @var string Запрашиваемый URL
     */
    private $url;

    /**
     * @var string HTTP-метод запроса
     */
    private $method;

    /**
     * @var array Список параметров
     */
    private $params = [];

    /**
     * @var Core\Response Объект класса Response
     */
    private $response;

    public function __construct()
    {
        $request = Request::getInstance();
        
        $this->url = rtrim($request->getRequestURI(), '/');
        $this->method = $request->getHTTPMethod();
        $this->response = Response::getInstance();
    }

    /**
     * Добавление в список маршрутов маршрут с HTTP-методом GET
     * 
     * @param string $pattern Шаблон строки URL
     * @param string $callback Функция обработки запроса
     */
    public function get($pattern, $callback)
    {
        $this->addRoute("GET", $pattern, $callback);
    }

    /**
     * Добавление в список маршрутов маршрут с HTTP-методом POST
     *  
     * @param string $pattern Шаблон строки URL
     * @param string $callback Функция обработки запроса
     */
    public function post($pattern, $callback)
    {
        $this->addRoute('POST', $pattern, $callback);
    }

    /**
     * Добавление маршрута в список маршрутов
     * 
     * @param string $method Шаблон строки URL
     * @param string $pattern Шаблон строки URL
     * @param string $callback Функция обработки запроса
     */
    public function addRoute($method, $pattern, $callback)
    {
        array_push($this->router, new Route($method, $pattern, $callback));
    }

    /**
     * Определяем совпадающий маршрут
     */
    private function setMatchRouters()
    {
        foreach ($this->router as $value) {
            // Проверяем, чтобы и HTTP-метод запроса совпадал и шаблон с URL
            if (strtoupper($this->method) == $value->getMethod()
                && $this->verifyPattern($this->url, $value->getPattern())) {
                $this->matchRouter = $value;
                break;
            }
        }
    }

    /**
     * Проверка на совпадение с паттерном
     *
     * @param string $url
     * @param string $pattern
     *
     * @return bool
     */
    public function verifyPattern($url, $pattern): bool
    {
        // Определяем набор необходимых параметров
        preg_match_all('@:([\w]+)@', $pattern, $params, PREG_PATTERN_ORDER);

        $patternAsRegex = preg_replace_callback('@:([\w]+)@', [$this, 'convertPatternToRegex'], $pattern);

        if (substr($pattern, -1) === '/' ) {
            $patternAsRegex = $patternAsRegex . '?';
        }
        $patternAsRegex = '@^' . $patternAsRegex . '$@';
        
        // Проверяем совпадение запроса с проверяемым url
        if (preg_match($patternAsRegex, $url, $paramsValue)) {
            array_shift($paramsValue);
            foreach ($params[0] as $key => $value) {
                $val = substr($value, 1);
                if ($paramsValue[$val]) {
                    $this->setParams($val, urlencode($paramsValue[$val]));
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Запись значений параметров запроса
     * 
     * @param string $key
     * @param string $value
     */
    private function setParams($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * Преобразование найденных значений в регулярное выражение
     * 
     * @param array $matches
     */
    private function convertPatternToRegex($matches)
    {
        $key = str_replace(':', '', $matches[0]);
        return '(?P<' . $key . '>[a-zA-Z0-9_\-\.\!\~\*\\\'\(\)\:\@\&\=\$\+,%]+)';
    }

    /**
     * Запуск маршрутизатора
     */
    public function run() 
    {
        if (empty($this->router)) {
            $this->sendNotFound('Not found routes');
        }

        $this->setMatchRouters();

        if (!empty($this->matchRouter)) {
            // Вызываем функцию обработки запроса в зависимости от ее типа
            if (is_callable($this->matchRouter->getCallback())) {
                call_user_func($this->matchRouter->getCallback(), $this->params);
            } else {
                $this->runController($this->matchRouter->getCallback(), $this->params);
            }
        } else {
            $this->sendNotFound('Not found route');
        }
    }

    /**
     * Запуск обработки запроса с использованием контроллера
     *
     * @param string $controller
     * @param array $params
     */
    private function runController($controller, $params)
    {
        $parts = explode('@', $controller);
        $controllerName = NAMESPACE_CONTROLLER . '\Controller' . ucfirst($parts[0]);

        $controller = new $controllerName();

        // Выбор функции в контролере
        if (isset($parts[1])) {
            $method = $parts[1];
            
            if (!method_exists($controller, $method))
                $this->sendNotFound('Not found controller\'s method');
            
        } else {
            $method = 'index';
        }

        // Вызов контроллера
        if (is_callable([$controller, $method])) {
            call_user_func([$controller, $method], $params);
        } else {
            $this->sendNotFound('Not found callable function');
        }
    }
    
    /**
     * Отправка сообщения об отсутствии маршрута
     * @param string $error Текст ошибки
     * 
     * @throws \Exception Невозможно выполнить запрос
     */
    private function sendNotFound(string $error)
    {
        $this->response->setStatus(404);
        throw new \Exception($error);
    }
}
