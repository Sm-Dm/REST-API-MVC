<?php

namespace App\Model\Entity;

use App\Model\Entity\OrderProduct;

/**
 * Сущность заказа
 */
class Order
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string Статус заказа
     */
    private $status;

    /**
     * @var array Список доступных статусов
     */
    public static $statuses = ['new', 'paid'];

    /**
     * @var array Список объектов товаров входящих в заказ
     */
    private $order_products = [];

    /**
     * Задание значений для сущности
     *
     * @param int    $id
     * @param string $status
     */
    public function __construct(int $id, string $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

    /**
     * Возвращение идентификатора товара
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращение статуса товара
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Установка статуса заказа
     *
     * @param  string $status
     * @throws \Exception
     */
    public function setStatus(string $status)
    {
        if (in_array($status, Order::$statuses)) {
            $this->status = $status;
        } else {
            throw new \Exception("Submitted invalid data for create Order's instance");
        }
    }

    /**
     * Получение списка товаров входящих в заказ
     *
     * @return array
     */
    public function getOrderProducts(): array
    {
        return $this->order_products;
    }

    /**
     * Добавление товара в заказ
     *
     * @param OrderProduct $orderProduct
     */
    public function addOrderProduct(OrderProduct $orderProduct)
    {
        $this->order_products[] = $orderProduct;
    }

    /**
     * Создание объекта заказа из массива
     *
     * @param  array $item Массив с данными о заказе
     * @throws \Exception Не хватает данных для создания объекта заказа
     * @return \App\Model\Entity\Order
     */
    public static function createFromArray(array $item): Order
    {
        if (isset($item['id'], $item['status']) 
            && $item['id'] > 0
            && in_array($item['status'], Order::$statuses)) {

            return new self($item['id'], $item['status']);
        } else {
            throw new \Exception("Submitted invalid data for create Order's instance");
        }
    }

    /**
     * Преобразование объекта заказа в массив данных
     *
     * @param self $item
     * @return array
     */
    public static function toArray(self $item): array
    {
        $orderProducts = [];
        foreach ($this->order_products as $orderProduct) {
            $orderProducts[] = OrderProduct::toArray($orderProduct);
        }
        return [
            'id' => $item->getId(),
            'status' => $item->getStatus(),
            'order_products' => $orderProducts
        ];
    }
}