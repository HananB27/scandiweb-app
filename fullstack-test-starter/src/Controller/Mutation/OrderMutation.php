<?php

namespace App\Controller\Mutation;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Controller\Models\Order;

class OrderMutation extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'OrderMutation',
            'fields' => [
                'createOrder' => [
                    'type' => Type::boolean(),
                    'args' => [
                        'customerName' => Type::nonNull(Type::string()),
                        'productIds' => Type::nonNull(Type::listOf(Type::string())),
                    ],
                    'resolve' => function($root, $args) {
                        return Order::createOrder($args['customerName'], $args['productIds']);  // Example function
                    },
                ],
            ],
        ];

        parent::__construct($config);
    }
}
