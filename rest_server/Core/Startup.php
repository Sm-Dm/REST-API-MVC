<?php

/**
 * Функция автозагрузки
 *
 * @param  string $class
 *
 * @throws Exception Класса нет в наличии
 */
function autoload($class) 
{
    $file = ROOT . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception(sprintf('Class { %s } Not Found!', $class));
    }
}

spl_autoload_register('autoload');