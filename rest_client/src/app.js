import Vue from 'vue';
window.Vue = Vue;

const product = (id, name, price) => ({id, name, price})

var app = new Vue({
    el: '#app',
    data: {
        products: [],
        checkedProducts: [],
        paid_order_id: null,
        paid_sum: null
    },
    created: function() {
        this.showProducts();
    },
    methods: {
        showProducts: function() {
            fetch('http://test.local/products/show')
                .then(function(response) {
                    if (response.ok) {
                        return response.json();
                    }

                    throw new Error('Network response was not ok');
                })
                .then(function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        app.products = [];
                        for (var i in data) {
                            app.products.push(product(data[i].id, data[i].name, data[i].price));
                        }
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        addProducts: function() {
            fetch('http://test.local/products/create')
                .then(function(response) {
                    if (response.ok) {
                        app.showProducts();
                        return true;
                    }

                    throw new Error('Network response was not ok');
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        createOrder: function() {
            var request_body = [];

            for (var i in this.checkedProducts) {
                request_body.push('product_ids[]=' + this.checkedProducts[i]);
            }

            fetch('http://test.local/order/create', {
                method: 'post',
                headers: {
                    "Content-type": "application/x-www-form-urlencoded; charset=UTF-8" 
                },
                body: request_body.join('&')
            }).then(function(response) {
                    if (response.ok) {
                        return response.json();
                    }

                    throw new Error('Network response was not ok');
                })
                .then(function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        alert('Создан заказ №' + data.order_id);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        paidOrder: function() {
            var request_body = [];
            request_body.push('order_id=' + this.paid_order_id);
            request_body.push('sum=' + this.paid_sum);

            fetch('http://test.local/order/paid', {
                method: 'post',
                headers: {
                    "Content-type": "application/x-www-form-urlencoded; charset=UTF-8" 
                },
                body: request_body.join('&')
            }).then(function(response) {
                    if (response.ok) {
                        response.json().then(function(data) {
                            alert(data.error);
                        }, function() {
                            alert('Заказ оплачен');
                        });
                    }

                    throw new Error('Network response was not ok');
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
});