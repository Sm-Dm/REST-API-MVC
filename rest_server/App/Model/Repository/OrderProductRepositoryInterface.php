<?php

namespace App\Model\Repository;

use App\Model\Entity\OrderProduct;

/**
 * Интерфейс для класса репозитория сущности товара в заказе
 */
interface OrderProductRepositoryInterface 
{
    /**
     * Добавление товара в заказ
     *
     * @param  \App\Model\Entity\OrderProduct $orderProduct
     * @return int Возвращает идентификатор добавленного товара в заказ
     */
    public function add(OrderProduct $orderProduct): int;

    /**
     * Следующий незанятый id
     *
     * @return int
     */
    public function nextId(): int;
} 
