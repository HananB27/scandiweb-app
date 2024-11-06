<?php

namespace App\Controller\Mutation;
require_once __DIR__  . "/../../../vendor/autoload.php";

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Controller\Models\Product;
use App\Controller\Type\PriceType;
use App\Controller\Type\CategoryType;
use App\Controller\Type\AttributeType;
use App\Controller\Type\GalleryType;
use App\Controller\Models\Order;
use GraphQL\Type\Definition\ResolveInfo;
use App\Controller\Type\OrderType;
use App\Controller\Models\Price;


class ProductMutation extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'Mutation',
            'fields' => [
    'id' => Type::nonNull(Type::string()),
    'name' => Type::string(),
    'description' => Type::string(),
    'inStock' => Type::int(),
    'price' => [
    'type' => new PriceType(),  // Define price as an object type
    'resolve' => function($product) {
        return Price::getPriceByProductId($product['id']);  // Fetch the price data
    }
],
    'category' => [
    'type' => new CategoryType(),  // Category is an object type
    'resolve' => function($product) {
        return Product::getCategoryById($product['category_id']);  // Fetch category data
    }
],
    'attributes' => [
        'type' => Type::listOf(new AttributeType()),  // Define attributes as a list of objects
        'resolve' => function($product) {
            return Product::getAttributesByProductId($product['id']);  // Fetch attributes data
        }
    ],
    'gallery' => [
        'type' => Type::listOf(new GalleryType()),  // Define gallery as a list of images
        'resolve' => function($product) {
            return Product::getGalleryByProductId($product['id']);  // Fetch gallery data
        }
    ],
    'brand' => Type::string(),  // Define brand as a string
    'createOrder' => [
    'type' => OrderType::getInstance(),
    'args' => [
        'customer_name' => Type::nonNull(Type::string()),
        'product_ids' => Type::nonNull(Type::listOf(Type::string())),
    ],
    'resolve' => function ($root, $args, $context, ResolveInfo $info) {
        try {
            error_log("createOrder called with args: " . json_encode($args));

            // Extract arguments
            $customerName = $args['customer_name'];
            $productIds = $args['product_ids'];

            // Call the createOrder method in the Order model
            $orderCreated = Order::createOrder($customerName, $productIds);
            error_log("Order created: " . json_encode($orderCreated));

            if (!$orderCreated) {
                throw new \Exception("Failed to create order");
            }

            // Fetch and return the created order details
            $orderDetails = Order::getOrderById($orderCreated['id']);
            error_log("Order details fetched: " . json_encode($orderDetails));
            return $orderDetails;
        } catch (\Exception $e) {
            error_log("Error in createOrder: " . $e->getMessage());  // Log error
            throw new \Exception("Internal server error: " . $e->getMessage());
        }
    }
],
            ],
        ];
        parent::__construct($config);
    }
}
