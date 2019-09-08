<?php

namespace App\Controller;

use App\Model\Service;
use App\Model\Repository;

/**
 * Контролер для обработки запросов связанные с заказами
 */
class ControllerOrders extends Controller
{
    /**
     * Создание заказа
     */
    public function create()
    {
        if (!empty($this->request->post('product_ids')) && is_array($this->request->post('product_ids'))) {
            $orderService = new Service\OrderService(
                new Repository\SqlOrderRepository(),
                new Repository\SqlProductRepository(),
                new Repository\SqlOrderProductRepository()
            );
            $order_id = $orderService->createOrder($this->request->post('product_ids'));
            $this->response->setContent(['order_id' => $order_id]);
            $this->response->setStatus(200);            
        } else {
            // Не были выбраны товары
            $this->response->setContent(['error' => 'No product selected']);
        }
    }

    /**
     * Оплата заказа
     * 
     * @throws Exception
     */
    public function paid()
    {
        $orderService = new Service\OrderService(
            new Repository\SqlOrderRepository(),
            new Repository\SqlProductRepository(),
            new Repository\SqlOrderProductRepository()
        );

        $order_id = (int)$this->request->post('order_id');
        $sum = (int)$this->request->post('sum');

        if (!$orderService->checkNeedPay($order_id)) { // Проверяем, нужно ли оплачивать переданный заказ
            $this->response->setContent(['error' => 'This order does not need to be paid']);
        } elseif (!$orderService->checkPaySum($order_id, $sum)) { // Равна ли указанная сумма сумме заказа
            $this->response->setContent(['error' => 'Invalid order sum']);
        } elseif (!$orderService->paid($order_id)) { // Получилось ли оплатить заказ
            $this->response->setContent(['error' => 'The order failed pay']);
        } else {
            $this->response->setStatus(200);
        }
    }
} 
