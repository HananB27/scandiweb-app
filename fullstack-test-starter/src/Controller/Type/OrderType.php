<?php

namespace App\Controller\Type;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Controller\Models\Order;
use App\Controller\Type\ProductType;
class OrderType extends ObjectType
{
    private static $instance = null;

    public function __construct()
    {
        $config = [
            'name' => 'Order',
            'fields' => [
                'id' => Type::nonNull(Type::int()),
                'customer_name' => Type::string(),
                'order_date' => Type::string(),
                'products' => [
                    'type' => Type::listOf(new ProductType()),
                    'resolve' => function($order) {
                        return Order::getProductsByOrderId($order['id']);
                    }
                ]
            ]
        ];

        parent::__construct($config);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
