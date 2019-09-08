<?php

namespace App\Model\Repository;

use App\Model\Entity\OrderProduct;

/**
 * Репозиторий сущности товара в заказе
 */
class SqlOrderProductRepository extends Repository implements OrderProductRepositoryInterface 
{
    /**
     * Добавление товара в заказ
     *
     * @param  \App\Model\Entity\OrderProduct $orderProduct
     * @return int
     */
    public function add(OrderProduct $orderProduct): int
    {
        $this->db->query('INSERT INTO `order_products` SET `id` = ?, `order_id` = ?, `product_id` = ?, `price` = ?', 
            [
                $orderProduct->getId(),
                $orderProduct->getOrderId(),
                $orderProduct->getProductId(),
                $orderProduct->getPrice()
            ]
        );

        return $this->db->getLastInsertId();
    }

    /**
     * Следующий незанятый id
     *
     * @return int
     */
    public function nextId(): int
    {
        $_order_products = $this->db->query('SELECT MAX(id) AS "max_id" FROM `order_products`');

        return (int)$_order_products->row['max_id'] + 1;
    }
}