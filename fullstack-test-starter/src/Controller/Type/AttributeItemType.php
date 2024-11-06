<?php

namespace App\Controller\Type;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class AttributeItemType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'AttributeItem',
            'fields' => [
                'displayValue' => Type::string(),
                'value' => Type::string(),
                'item_id' => Type::string(),
                '__typename' => Type::string()
            ],
        ];

        parent::__construct($config);
    }
}