<?php

namespace App\Controller;

use App\Model\Service;
use App\Model\Repository;

/**
 * Контролер для обработки запросов связанные с товарами
 */
class ControllerProducts extends Controller
{
    /**
     * Создаем 20 товаров
     */
    public function create()
    {
        $productService = new Service\ProductService(new Repository\SqlProductRepository());
        $productService->generateList(20);
        $this->response->setStatus(200);
    }

    /**
     * Передаем полный список товаров
     */
    public function show()
    {
        $productService = new Service\ProductService(new Repository\SqlProductRepository());
        $this->response->setContent($productService->getAll());
        $this->response->setStatus(200);
    }
}