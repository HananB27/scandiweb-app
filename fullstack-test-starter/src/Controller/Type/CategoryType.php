<?php

namespace App\Controller\Type;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;class CategoryType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'Category',
            'fields' => [
                'id' => Type::nonNull(Type::id()),
                'name' => Type::string(),
            ],
        ];
        parent::__construct($config);
    }
}