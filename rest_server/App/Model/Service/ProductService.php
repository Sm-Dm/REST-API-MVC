<?php

namespace App\Model\Service;

use App\Model\Entity\Product;
use App\Model\Repository\ProductRepositoryInterface;

/**
 * Сервис для работы с товарами
 */
class ProductService
{
    /**
     * @var \App\Model\Repository\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * Задаем репозитории через их интерфейсы
     *
     * @param \App\Model\Repository\ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Создаем список товаров
     *
     * @param int $qnt создаваемое кол-во товаров
     */
    public function generateList(int $qnt)
    {
        for ($i = 1; $i <= $qnt; $i++) {
            $this->productRepository->beginTransaction();
            $id = $this->productRepository->nextId();

            $this->productRepository->add(new Product(
                    $id,
                    'Товар'.$id, 
                    $this->generatePrice()
                )
            );

            $this->productRepository->commitTransaction();
        }
    }

    /**
     * Получаем массив всех товаров
     *
     * @return array
     */
    public function getAll()
    {
        // Массив товаров представленных в виде массива
        $products = [];
        foreach ($this->productRepository->getAll() as $product) {
            $products[] = Product::toArray($product);
        }

        return $products;
    }

    /**
     * Генерируем случайную цену
     *
     * @return int
     */
    private function generatePrice()
    {
        return rand(1, 1000) * 10;
    }
}