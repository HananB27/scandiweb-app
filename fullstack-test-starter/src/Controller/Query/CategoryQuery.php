<?php

namespace App\Controller\Query;
require_once __DIR__  . "/../../../vendor/autoload.php";

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Controller\Models\Category;

class CategoryQuery extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'CategoryQuery',
            'fields' => [
                'categories' => [
                    'type' => Type::listOf(new ObjectType([
                        'name' => 'Category',
                        'fields' => [
                            'id' => Type::nonNull(Type::int()),
                            'name' => Type::nonNull(Type::string()),
                        ],
                    ])),
                    'resolve' => function() {
                        return Category::getAllCategories(); // Fetch categories from the database
                    },
                ],
            ],
        ];
        parent::__construct($config);
    }
}
