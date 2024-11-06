<?php

namespace App\Controller\Query;

use App\Controller\Models\Product;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Controller\Type\ProductType;



class ProductQuery extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'ProductQuery',
            'fields' => [
                'products' => [
                    'type' => Type::listOf(new ProductType()),
                    'resolve' => function () {
                        return Product::getAllProducts();
                    },
                ],
            ],
        ];

        parent::__construct($config);
    }
}
