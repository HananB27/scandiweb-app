<?php

ini_set("log_errors", 1);
ini_set("error_log", __DIR__ . "/php_error_log.txt") ;

require __DIR__ . '/../../vendor/autoload.php';

use App\Controller\Models\Product;
use App\Controller\Models\Price;

// Define the product ID you want to test
$productId = "jacket-canada-goosee";
echo "Testing with Product ID: {$productId}\n";

// Test the getPriceByProductId method
try {
    $priceData = Price::getPriceByProductId($productId);

    if ($priceData === null) {
        echo "No price data found for product ID {$productId}.\n";
    } else {
        echo "Price data for product ID {$productId}:\n";
        print_r($priceData);
    }
} catch (Throwable $e) {
    echo "Error retrieving price data: " . $e->getMessage() . "\n";
}

// Test the getAttributes method
try {
    $attributes = Product::getAttributes($productId);

    if (empty($attributes)) {
        echo "No attributes found for product ID {$productId}.\n";
    } else {
        echo "Attributes for product ID {$productId}:\n";
        print_r($attributes);
    }
} catch (Throwable $e) {
    echo "Error retrieving attributes: " . $e->getMessage() . "\n";
}

// Test the getGallery method
try {
    $gallery = Product::getGallery($productId);

    if (empty($gallery)) {
        echo "No gallery images found for product ID {$productId}.\n";
    } else {
        echo "Gallery images for product ID {$productId}:\n";
        print_r($gallery);
    }
} catch (Throwable $e) {
    echo "Error retrieving gallery images: " . $e->getMessage() . "\n";
}
