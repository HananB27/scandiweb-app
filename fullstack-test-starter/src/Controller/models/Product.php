<?php

namespace App\Controller\Models;
require_once __DIR__  . "/../../../vendor/autoload.php";

use App\Controller\Config\Database;

class Product
{   
    private $conn;

    public $id;
    public $name;
    public $description;
    public $price;
    public $inStock;

    // Constructor to initialize product data
    public function __construct($data) {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->price = $data['price'];
        $this->inStock = $data['inStock'];
    }

    // Fetch all products
    public static function getAllProducts() {
        $database = new Database();
         $db = $database ->connect();
        $result = $db->query('SELECT * FROM products');
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }


    // Fetch attributes for a product
    public static function getAttributes($productId) {
        $database = new Database();
        $conn = $database->connect();
    
        $query = "SELECT id, name, item_type, __typename FROM attributes WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $productId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $attributes = [];
        while ($row = $result->fetch_assoc()) {
            $row['items'] = self::getAttributeItems($row['id']); // Fetch attribute items for each attribute
            $attributes[] = $row;
        }
    
        if (empty($attributes)) {
            error_log(message: "No attributes found for product ID: " . $productId);
        }
    
        return $attributes;
    }
    

    // Fetch prices for a product
    public static function getPrice($productId) {
        $database = new Database();
        $conn = $database->connect();
    
        $query = "SELECT amount, currency_id FROM prices WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $productId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $price = $result->fetch_assoc();
        if ($price) {
            return $price;
        } else {
            error_log("No price found for product ID: " . $productId);
            return null;
        }
    }
    

    // Fetch gallery images for a product
    public static function getGallery($productId) {
        $database = new Database();
        $conn = $database->connect();
    
        $productId = trim($productId);
    
        // Use a parameterized query to ensure secure handling of the product ID
        $query = "SELECT image_url FROM product_galleries WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $productId); // Bind product ID as a string
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result === false) {
            return [];
        }
    
        $gallery = [];
        while ($row = $result->fetch_assoc()) {
            // Ensure each gallery item is structured as an associative array
            $gallery[] = ['image_url' => trim($row['image_url'])];
        }
        
        return $gallery;
    }

    // Add a new product to the database
    public static function addProduct($data) {
        $database = new Database();
        $conn = $database->connect();

        $query = "INSERT INTO products (id, name, description, inStock, category_id, brand) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            'ssssis',
            $data['id'],
            $data['name'],
            $data['description'],
            $data['inStock'],
            $data['category_id'],
            $data['brand']
        );

        if ($stmt->execute()) {
            return new Product($data);
        } else {
            return null;
        }
    }

    // Update an existing product in the database
    public static function updateProduct($data) {
        $database = new Database();
        $conn = $database->connect();

        // SQL query to update the product
        $query = "UPDATE products 
                  SET name = '{$data['name']}', description = '{$data['description']}', 
                      inStock = {$data['inStock']}, category_id = '{$data['category_id']}', brand = '{$data['brand']}' 
                  WHERE id = '{$data['id']}'";

        if ($conn->query($query)) {
            return new Product($data);  // Return the updated product
        } else {
            return null;  // In case of failure
        }
    }

    public static function getCategoryById($categoryId) {
        $database = new Database();
        $conn = $database->connect();
    
    
        // Check if the category ID is valid before querying
        if (!$categoryId) {
            return null;
        }
    
        // Query to fetch category from the database
        $query = "SELECT id, name FROM categories WHERE id = '$categoryId'";
        $result = $conn->query($query);
    
        // Check if the query returned a result
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();  // Return the category as an associative array
        } else {
            return null;  // Return null if category not found
        }
    }
    
    public static function getAttributesByProductId($productId) {
        $database = new Database();
        $conn = $database->connect();
    
        // SQL query to fetch attributes for the given product
        $query = "SELECT name, value FROM attributes WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $productId);  // Bind the product ID as a string
        $stmt->execute();
        $result = $stmt->get_result();
    
        $attributes = [];
        while ($row = $result->fetch_assoc()) {
            $attributes[] = $row;
        }
    
        return $attributes;
    }

    public static function getAttributeItems($attributeId) {
        $database = new Database();
        $conn = $database->connect();
    
        $query = "SELECT displayValue, value, item_id, __typename FROM attribute_items WHERE attribute_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $attributeId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $attributeItems = [];
        while ($row = $result->fetch_assoc()) {
            $attributeItems[] = $row;
        }
    
        return $attributeItems;
    }
    public static function getGalleryByProductId($productId) {
        $database = new Database();
        $conn = $database->connect();
    
        $query = "SELECT image_url FROM product_galleries WHERE product_id = '$productId'";
        $result = $conn->query($query);
    
        $gallery = [];
        while ($row = $result->fetch_assoc()) {
            $gallery[] = $row['image_url'];  // Collect image URLs
        }
    
        return $gallery;
    }

}
