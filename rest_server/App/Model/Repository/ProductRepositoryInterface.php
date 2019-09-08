<?php

namespace App\Model\Repository;

use App\Model\Entity\Product;

interface ProductRepositoryInterface
{
    /**
     * Добавление товара
     *
     * @param  \App\Model\Entity\Product $product
     * @return int Возвращает идентификатор заказа
     */
    public function add(Product $product): int;

    /**
     * Получение списка товаров
     *
     * @return array
     */
    public function getAll(): array;

    /**
     * Получение сущности товара по id
     *
     * @param  int $product_id
     * @return Product
     */
    public function getProductById($product_id): Product;

    /**
     * Следующий незанятый id
     *
     * @return int
     */
    public function nextId(): int;
}