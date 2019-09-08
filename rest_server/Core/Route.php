<?php

namespace Core;

/**
 * Класс для хранения маршрутов
 */
class Route {
    
    /**
     * @var string HTTP-метод
     */
    private $method;

    /**
     * @var string URL-шаблона
     */
    private $pattern;

    /**
     * @var mixed Функция обработки запроса
     */
    private $callback;

    public function __construct(string $method, string $pattern, $callback)
    {
        $this->method = strtoupper($method);
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    /**
     * Получение HTTP-метода
     * 
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Получение URL-шаблона
     * 
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * Получение функции обработки запроса
     * 
     * @return callable|string
     */
    public function getCallback()
    {
        return $this->callback;
    }
}