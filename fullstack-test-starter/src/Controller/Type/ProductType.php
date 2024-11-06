<?php

namespace App\Controller\Type;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Controller\Models\Product;
use App\Controller\Models\Price;
use App\Controller\Type\CategoryType;
use App\Controller\Type\AttributeType;
use App\Controller\Type\PriceType;
use App\Controller\Type\GalleryType;

class ProductType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'Product',
            'fields' => [
                'id' => Type::nonNull(Type::string()),
                'name' => Type::string(),
                'description' => Type::string(),
                'inStock' => Type::int(),
                'brand' => Type::string(),
                'category' => [
                    'type' => new CategoryType(),
                    'resolve' => function($product) {
                        if (isset($product['category_id'])) {
                            error_log("Fetching category for product ID: " . $product['id']);
                            return Product::getCategoryById($product['category_id']);
                        }
                        error_log("Category ID not set for category resolver");
                        return null;
                    }
                ],
                'price' => [
                    'type' => new PriceType(),
                    'resolve' => function($product) {
                        if (isset($product['id'])) {
                            error_log("Fetching price for product ID: " . $product['id']);
                            $price = Product::getPrice($product['id']);
                            if ($price) {
                                $price['currency'] = Price::getCurrencyById($price['currency_id']);
                                return $price;
                            }
                            error_log("No price found for product ID: " . $product['id']);
                            return null;
                        }
                        error_log("Product ID not set for price resolver");
                        return null;
                    }
                ],
                'attributes' => [
                    'type' => Type::listOf(new AttributeType()),
                    'resolve' => function($product) {
                        if (isset($product['id'])) {
                            error_log("Attempting to fetch attributes for product ID: " . $product['id']);
                            $attributes = Product::getAttributes($product['id']);
                            if (empty($attributes)) {
                                error_log("No attributes found for product ID: " . $product['id']);
                            } else {
                                error_log("Attributes found for product ID: " . $product['id']);
                            }
                            return $attributes;
                        }
                        error_log("Product ID not set for attributes resolver");
                        return [];
                    }
                ],
                'product_galleries' => [
                    'type' => Type::listOf(new GalleryType()),
                    'resolve' => function($product) {
                        if (isset($product['id'])) {
                            error_log("Attempting to fetch gallery for product ID: " . $product['id']);
                            $gallery = Product::getGallery($product['id']);
                            if (empty($gallery)) {
                                error_log("No gallery images found for product ID: " . $product['id']);
                            } else {
                                error_log("Gallery images found for product ID: " . $product['id']);
                            }
                            return $gallery;
                        }
                        error_log("Product ID not set for gallery resolver");
                        return [];
                    }
                ]
            ],
        ];

        parent::__construct($config);
    }
}
