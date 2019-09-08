<?php

namespace App\Model\Repository;

use Core\Database\PDOAdapter;

/**
 * Базовый класс для репозиториев
 */
class Repository
{
    /**
     * Для хранения объекта взаимодействия с БД
     *
     * @var mixed
     */
    public $db;

    public function __construct()
    {
        $this->db = PDOAdapter::getInstance();
        // Настраиваем соединение с БД здесь, чтобы соединяться только по необходимости.
        // Если соединение уже произошло, то повторно соединяться он не будет
        $this->db->connect(
            CONFIG_DB['Host'], 
            CONFIG_DB['User'], 
            CONFIG_DB['Password'], 
            CONFIG_DB['Db'], 
            CONFIG_DB['Port']
        );
    }

    /**
     * Инициализация транзакции
     */
    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    /**
     * Зафиксировать транзакцию
     */    
    public function commitTransaction()
    {
        $this->db->commitTransaction();
    }

    /**
     * Откатить транзакцию
     */
    public function rollbackTransaction()
    {
        $this->db->rollBackTransaction();
    }
}