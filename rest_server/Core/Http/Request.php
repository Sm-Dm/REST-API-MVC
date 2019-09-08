<?php

namespace Core\Http;

class Request
{
    use \Singleton;

    /**
     * Получение $_GET параметров
     *
     * @param  string $key
     * @return string|array
     */
    public function get(string $key = null)
    {
        if ($key !== null) {
            return isset($_GET[$key]) ? $_GET[$key] : null;
        }

        return $_GET;
    }

    /**
     *  Получение $_POST параметров
     *
     * @param  string $key
     * @return string|array
     */
    public function post(string $key = null)
    {
        if ($key !== null)
            return isset($_POST[$key]) ? $_POST[$key] : null;

        return $_POST;
    }

    /**
     *  Получение HTTP-метода (POST, GET ...) 
     *
     * @return string
     */
    public function getHTTPMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     *  Строка запроса
     *
     * @return string
     */
    public function getRequestURI(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
}
