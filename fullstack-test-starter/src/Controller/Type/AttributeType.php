<?php

namespace App\Controller\Type;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Controller\Models\Product;

class AttributeType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'Attribute',
            'fields' => [
                'id' => Type::nonNull(Type::int()),
                'name' => Type::string(),
                'items' => [
                    'type' => Type::listOf(new AttributeItemType()),
                    'resolve' => function($attribute) {
                        // Assuming $attribute['id'] is the attribute ID
                        return Product::getAttributeItems($attribute['id']);
                    }
                ]
            ],
        ];

        parent::__construct($config);
    }
}
