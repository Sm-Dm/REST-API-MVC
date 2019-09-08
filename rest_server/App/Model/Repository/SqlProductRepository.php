<?php

namespace App\Model\Repository;

use App\Model\Entity\Product;

/**
 * Репозиторий сущности товара
 */
class SqlProductRepository extends Repository implements ProductRepositoryInterface 
{
    /**
     * Добавление товара
     *
     * @param  \App\Model\Entity\Product $product
     * @return int Возвращает идентификатор заказа
     */
    public function add(Product $product): int
    {
        $this->db->query('INSERT INTO `products` SET `id` = ?, `name` = ?, `price` = ?', [
            $product->getId(),
            $product->getName(),
            $product->getPrice()
        ]);

        return $this->db->getLastInsertId();
    }

    /**
     * Получение списка товаров
     *
     * @return array
     */
    public function getAll(): array
    {
        $products = [];

        $_products = $this->db->query('SELECT `id`, `name`, `price` FROM `products`');

        foreach ($_products->rows as $_product) {
            $products[] = Product::createFromArray($_product);
        }

        return $products;
    }

    /**
     * Получение сущности товара по id
     *
     * @param  int $product_id
     * @return Product
     */
    public function getProductById($product_id): Product
    {
        $_product = $this->db->query(
            'SELECT `id`, `name`, `price` FROM `products` WHERE `id` = ?', 
            [$product_id]
        );

        return Product::createFromArray($_product->row);
    }

    /**
     * Следующий незанятый id
     *
     * @return int
     */
    public function nextId(): int
    {
        $_product = $this->db->query('SELECT MAX(id) AS "max_id" FROM `products`');

        return (int)$_product->row['max_id'] + 1;
    }
} 
