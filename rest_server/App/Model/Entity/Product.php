<?php

namespace App\Model\Entity;

/**
 * Сущность товара
 */
class Product
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string Наименование товара
     */
    private $name;

    /**
     * @var int Стоимость товара
     */
    private $price;

    /**
     * Задание значений для сущности
     *
     * @param int    $id
     * @param string $name
     * @param int    $price
     */
    public function __construct(int $id, string $name, int $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
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
     * Возвращение имени товара
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Установка наименования товара
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Возвращение цены товара
     *
     * @return string
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * Установка цены товара
     */
    public function setPrice()
    {
        $this->price = $price;
    }

    /**
     * Создание объекта товара из массива
     *
     * @param  array $item Массив с данными о товаре
     * @throws \Exception Не хватает данных для создания объекта товара
     * @return \App\Model\Entity\Product
     */
    public static function createFromArray(array $item): Product
    {
        if (isset($item['id'], $item['name'], $item['price']) 
            && $item['id'] > 0
            && !empty($item['name'])) {
            return new self($item['id'], $item['name'], $item['price']);
        } else {
            throw new \Exception("Submitted invalid data for create Product's instance");
        }
    }

    /**
     * Преобразование объекта товара в массив данных
     *
     * @param self $item
     * @return array
     */
    public static function toArray(self $item): array
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'price' => $item->getPrice()
        ];
    }
}