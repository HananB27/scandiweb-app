<?php
namespace App\Controllers;
require_once __DIR__  . "/../../../vendor/autoload.php";

use App\Controller\Models\Product;
use App\Controller\Config\Database;

class ProductController {
    private $db;

    // Constructor to inject the database connection
    public function __construct($db) {
        $this->db = $db;
    }

    // Method to fetch all products
    public function getAllProducts() {
        $database = new Database();
        $db = $database->connect();
        $result = $db->query('SELECT * FROM products');
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = new Product($row); // Assuming Product can accept an associative array
        }

        return $products;
    }
}
