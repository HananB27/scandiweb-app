<?php

// PriceType.php
namespace App\Controller\Type;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Controller\Models\Price;

class PriceType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'Price',
            'fields' => [
                'amount' => Type::float(),
                'currency' => [
                    'type' => new CurrencyType(),
                    'resolve' => function($price) {
                        // Assuming $price['currency_id'] is set
                        return Price::getCurrencyById($price['currency_id']);
                    }
                ]
            ],
        ];

        parent::__construct($config);
    }
}
