<?php

namespace App\Controller\Type;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class GalleryType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'Gallery',
            'fields' => [
                'image_url' => Type::string(),
            ],
        ];

        parent::__construct($config);
    }
}

