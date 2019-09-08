<?php

namespace App\Model\Repository;

use App\Model\Entity\Order;

/**
 * Интерфейс для класса репозитория сущности товара в заказе
 */
interface OrderRepositoryInterface
{
    /**
     * Добавление заказа
     *
     * @return int Возвращает идентификатор заказа
     */
    public function add(): int;

    /**
     * Получения сущности заказа по его id
     *
     * @param  int $order_id
     * @return \App\Model\Entity\Order
     */
    public function getOrderById(int $order_id): Order;

    /**
     * Получение суммы заказа по его id
     * 
     * @param  int $order_id
     * @return int
     */
    public function getOrderSum(int $order_id): int;

    /**
     * Изменение статуса заказа
     *
     * @param int    $order_id
     * @param string $status
     */
    public function changeStatus(int $order_id, string $status);

    /**
     * Следующий незанятый id
     *
     * @return int
     */
    public function nextId(): int;
}
