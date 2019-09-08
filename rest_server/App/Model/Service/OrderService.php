<?php

namespace App\Model\Service;

use App\Model\Entity\Product;
use App\Model\Entity\Order;
use App\Model\Entity\OrderProduct;
use App\Model\Repository\ProductRepositoryInterface;
use App\Model\Repository\OrderRepositoryInterface;
use App\Model\Repository\OrderProductRepositoryInterface;

/**
 * Сервис для работы с заказами
 */
class OrderService
{

    /**
     * @var \App\Model\Repository\OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var \App\Model\Repository\ProductRepositoryInterface
     */
    private $productRepository;


    /**
     * @var \App\Model\Repository\OrderProductRepositoryInterface
     */
    private $orderProductRepository;

    /**
     * Задаем репозитории через их интерфейсы
     *
     * @param \App\Model\Repository\OrderRepositoryInterface $orderRepository
     * @param \App\Model\Repository\ProductRepositoryInterface $productRepository
     * @param \App\Model\Repository\OrderProductRepositoryInterface $orderProductRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository, 
        ProductRepositoryInterface $productRepository, 
        OrderProductRepositoryInterface $orderProductRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->orderProductRepository = $orderProductRepository;

    }

    /**
     * Создание заказа
     * 
     * @param  array $product_ids Идентификаторы добавляемых товаров
     * @return int Возвращаем id заказа
     */
    public function createOrder(array $product_ids): int
    {
        try {
            $this->orderRepository->beginTransaction();
            // Создаем заказ
            $order_id = $this->orderRepository->add();
            // Добавляем в заказ товары
            foreach ($product_ids as $product_id) {
                $product = $this->productRepository->getProductById($product_id);

                $orderProduct = new OrderProduct(
                    $this->orderProductRepository->nextId(),
                    $order_id,
                    $product_id,
                    $product->getPrice()
                );
                $this->orderProductRepository->add($orderProduct);
            }
            $this->orderRepository->commitTransaction();
        } catch (Throwable $e) {
            // Если что-то пошло не так, то полностью откатываем создание заказа
            $this->orderRepository->rollbackTransaction();
            throw new \Exception('Error creating new order');
        }
        return $order_id;
    }

    /**
     * Проверка, что заказ нужно оплатить
     *
     * @param int $order_id
     * @return bool
     */
    public function checkNeedPay(int $order_id): bool
    {
        $order = $this->orderRepository->getOrderById($order_id);

        return ($order->getStatus() == 'new');
    }

    /**
     * Проверка, что переданная сумма равна сумме заказа
     *
     * @param int $order_id
     * @param int $sum
     * @return bool
     */
    public function checkPaySum(int $order_id, int $sum): bool
    {
        return ($sum == $this->orderRepository->getOrderSum($order_id));
    }

    /**
     * Оплата заказа
     *
     * @param int $order_id
     * @return bool
     */
    public function paid(int $order_id)
    {
        $headers = get_headers('http://ya.ru');

        if ($headers && preg_grep('/\s200\s/', $headers)) {
            $this->orderRepository->changeStatus($order_id, 'paid');
            return true;
        } else {
            return false;
        }
    }
}