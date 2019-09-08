<?php

$router->get('/products/create', 'products@create');
$router->get('/products/show', 'products@show');

$router->post('/order/create', 'orders@create');
$router->post('/order/paid', 'orders@paid');

$router->get('/', function() {
    echo 'Welcome to API!';
});
