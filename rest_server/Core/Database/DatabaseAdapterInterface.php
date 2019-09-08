<?php

namespace Core\Database;

/**
 * Интерфейс адаптера СУБД
 */
interface DatabaseAdapterInterface
{
	/**
	 * Соединение с БД
	 *
	 * @param string $hostname 
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 * @param string $port
	 */
    public function connect(string $hostname, string $username, string $password, string $database, string $port);

    /**
     * Выполнение запроса
     *
     * @param string $sql    Запрос
     * @param array  $params Параметры
     */
    public function query($sql, $params = array());

    /**
     * Экранирование входной строки
     *
     * @param mixed $value
     */
    public function escape($value);

    /**
     * Получение id последней вставленной записи
     */
    public function getLastInsertId();

    /**
     * Инициализация транзакции
     */
    public function beginTransaction();

    /**
     * Зафиксировать транзакцию
     */    
    public function commitTransaction();

    /**
     * Откатить транзакцию
     */
    public function rollbackTransaction();

    /**
     * Закрыть соединение с БД
     */
    public function closeConnection();
}