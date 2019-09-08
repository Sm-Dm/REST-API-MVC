<?php

namespace App\Model\Repository;

use App\Model\Entity\Order;

/**
 * Репозиторий сущности заказа
 */
class SqlOrderRepository extends Repository implements OrderRepositoryInterface 
{
    /**
     * Добавление заказа
     *
     * @return int Возвращает идентификатор заказа
     */
    public function add(): int
    {
        $this->db->query('INSERT INTO `orders` SET `status` = "new"');

        return $this->db->getLastInsertId();
    }

    /**
     * Получения сущности заказа по его id
     *
     * @param  int $order_id
     * @return \App\Model\Entity\Order
     */
    public function getOrderById(int $order_id): Order
    {
        $_order = $this->db->query(
            'SELECT `id`, `status` FROM `orders` WHERE `id` = ?', 
            [$order_id]
        );
        if (!$_order->num_rows) {
            throw new \Exception("Order not found");
        }

        return Order::createFromArray($_order->row);
    }

    /**
     * Получение суммы заказа по его id
     * 
     * @param  int $order_id
     * @return int
     */
    public function getOrderSum(int $order_id): int
    {
        $_item = $this->db->query('
            SELECT SUM(o_p.`price`) AS "sum"
            FROM `order_products` o_p
            INNER JOIN `products` p ON p.`id` = o_p.`product_id`
            WHERE `order_id` = ?
            GROUP BY `order_id`', 
            [$order_id]
        );

        if (!$_item->num_rows) {
            throw new \Exception("Order not found");
        }

        return $_item->row['sum'];
    }

    /**
     * Изменение статуса заказа
     *
     * @param int    $order_id
     * @param string $status
     */
    public function changeStatus(int $order_id, string $status)
    {
        if (in_array($status, Order::$statuses)) {
            $this->db->query(
                'UPDATE `orders` SET `status` = ? WHERE `id` = ?', 
                [$status, $order_id]
            );
        } else {
            throw new \Exception("Submitted invalid status");
        }
    }

    /**
     * Следующий незанятый id
     *
     * @return int
     */
    public function nextId(): int
    {
        $_order = $this->db->query('SELECT MAX(id) AS "max_id" FROM `orders`');

        return (int)$_order->row['max_id'] + 1;
    }
}