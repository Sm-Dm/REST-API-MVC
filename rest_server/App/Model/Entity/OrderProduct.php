<?php

namespace App\Model\Entity;

/**
 * Сущность товара из заказа
 */
class OrderProduct
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int Идентификатор заказа
     */
    private $order_id;

    /**
     * @var int Идентификатор товара
     */
    private $product_id;

    /**
     * @var string Цена товара
     */
    private $price;

    /**
     * Задание значений для сущности
     *
     * @param int $id
     * @param string $order_id
     * @param int $product_id
     * @param int $price
     */
    public function __construct(int $id, string $order_id, int $product_id, int $price)
    {
        $this->id = $id;
        $this->order_id = $order_id;
        $this->product_id = $product_id;
        $this->price = $price;
    }

    /**
     * Возвращение идентификатора товара в заказе
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращение идентификатор заказа
     *
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->order_id;
    }

    /**
     * Устанавливает идентификатор заказа
     *
     * @param int $order_id
     */
    public function setOrderId(int $order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Устанавливает идентификатор товара
     *
     * @return int
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /**
     * Возвращение цены товара в заказе
     *
     * @return string
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * Установка цены товара в заказе
     */
    public function setPrice()
    {
        $this->price = $price;
    }

    /**
     * Создание объекта товара в заказе из массива
     *
     * @param  array $item Массив с данными о товаре в заказе
     * @throws \Exception Не хватает данных для создания объекта товара в заказе
     * @return \App\Model\Entity\OrderProduct
     */
    public static function createFromArray(array $item): OrderProduct
    {
        if (isset($item['id'], $item['order_id'], $item['product_id'], $item['price']) 
            && $item['id'] > 0
            && $item['order_id'] > 0
            && $item['product_id'] > 0
            && $item['price'] >= 0) {
            return new self($item['id'], $item['order_id'], $item['product_id'], $item['price']);
        } else {
            throw new \Exception("Submitted invalid data for create OrderProduct's instance");
        }
    }

    /**
     * Преобразование объекта товара в заказе в массив данных
     *
     * @param self $item
     * @return array
     */
    public static function toArray(self $item): array
    {
        return [
            'id' => $item->getId(),
            'order_id' => $item->getOrderId(),
            'product_id' => $item->getProductId(),
            'price' => $item->getPrice()
        ];
    }
}