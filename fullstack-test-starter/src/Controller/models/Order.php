<?php

namespace App\Controller\Models;
require_once __DIR__  . "/../../../vendor/autoload.php";

use App\Controller\Config\Database;

class Order
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    

    // Create a new order
    public static function createOrder($customerName, $productIds)
{
    $database = new Database();
    $conn = $database->connect();

    // Check if all product IDs exist in the `products` table
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $query = "SELECT id FROM products WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('s', count($productIds)), ...$productIds); // Bind as strings
    $stmt->execute();
    $existingProducts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // If any product ID is missing, throw an exception
    if (count($existingProducts) < count($productIds)) {
        throw new \Exception("One or more product IDs do not exist in the products table");
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert the order into the orders table
        $query = "INSERT INTO orders (customer_name) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $customerName);
        $stmt->execute();

        // Get the last inserted order ID
        $orderId = $conn->insert_id;

        // Insert each product into the order_products table
        $query = "INSERT INTO order_products (order_id, product_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        foreach ($productIds as $productId) {
            $stmt->bind_param("is", $orderId, $productId); // Bind product_id as a string
            $stmt->execute();
        }

        // Commit the transaction
        $conn->commit();

        // Return the created order ID to fetch the order details
        return ['id' => $orderId];
    } catch (\Exception $e) {
        // Roll back the transaction if something went wrong
        $conn->rollback();
        error_log("Error in Order::createOrder: " . $e->getMessage());  // Log error
        throw $e;  // Re-throw the exception for GraphQL to catch
    }
}

    // Fetch products by order ID
    public static function getProductsByOrderId($orderId)
    {
        $database = new Database();
        $conn = $database->connect();

        $query = "SELECT p.* FROM products p 
                  JOIN order_products op ON op.product_id = p.id 
                  WHERE op.order_id = ?";
        
        $statement = $conn->prepare($query);
        $statement->bind_param('i', $orderId);
        $statement->execute();

        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Fetch order by ID (including customer name, date, and products)
    public static function getOrderById($orderId)
    {
        $database = new Database();
        $conn = $database->connect();

        // Fetch order details
        $orderQuery = "SELECT * FROM orders WHERE id = ?";
        $orderStmt = $conn->prepare($orderQuery);
        $orderStmt->bind_param('i', $orderId);
        $orderStmt->execute();
        $order = $orderStmt->get_result()->fetch_assoc();

        if (!$order) {
            return null;
        }

        // Fetch products for this order
        $order['products'] = self::getProductsByOrderId($orderId);

        return $order;
    }
}
