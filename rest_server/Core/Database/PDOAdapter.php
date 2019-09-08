<?php

namespace Core\Database;

/**
 *  Адаптер для работы c PDO
 */
class PDOAdapter implements DatabaseAdapterInterface
{
    use \Singleton;

    /**
     * @var \PDO
     */
    private $pdo = null;

    /**
     * Создание соединения c БД
     *
     * @param string $hostname  
     * @param string $username  
     * @param string $password  
     * @param string $database
     * @param string $port      
     */
    public function connect(string $hostname, string $username, string $password, string $database, string $port)
    {
        if (!$this->pdo) {
            $this->pdo = new \PDO("mysql:host=" . $hostname . ";port=" . $port . ";dbname=" . $database, $username, $password, array(\PDO::ATTR_PERSISTENT => true));

            $this->pdo->exec("SET NAMES 'utf8'");
            $this->pdo->exec("SET CHARACTER SET utf8");
            $this->pdo->exec("SET CHARACTER_SET_CONNECTION=utf8");
            $this->pdo->exec("SET SQL_MODE = ''");
        }
    }
    
    /**
     * Запрос к БД
     *
     * @param   string    $sql    Шаблон sql-запроса
     * @param   array     $params Параметры для sql-запроса
     * @return  array|boolean
     */
    public function query($sql, $params = array())
    {
        $statement = $this->pdo->prepare($sql);
        $result = new \stdClass();
        if ($statement && $statement->execute($params)) {
            $data = array();

            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            $result->row = (isset($data[0]) ? $data[0] : array());
            $result->rows = $data;
            $result->num_rows = $statement->rowCount();
        } else {
            $result->row = array();
            $result->rows = array();
            $result->num_rows = 0;
        }

        return $result;
    }

    /**
     * Экранирование значения
     * 
     * @param mixed $value
     */
    public function escape($value)
    {
        return $this->pdo->quote($value);
    }

    /**
     * Возвращение id последней вставленной записи в БД
     */
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Инициализация транзакции
     */
    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    /**
     * Зафиксировать транзакцию
     */    
    public function commitTransaction()
    {
        $this->pdo->commit();
    }

    /**
     * Откатить транзакцию
     */
    public function rollbackTransaction()
    {
        $this->pdo->rollBack();
    }

    /**
     * Закрываем соединение с БД
     */
    public function closeConnection()
    {
        $this->pdo = null;
    }
}
