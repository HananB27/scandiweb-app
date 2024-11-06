<?php

namespace App\Controller\Models;
require_once __DIR__  . "/../../../vendor/autoload.php";

use App\Controller\Config\Database;

class Category
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Fetch all categories
    public static function getAllCategories()
    {
        $database = new Database();
        $conn = $database->connect();

        $query = "SELECT * FROM categories";
        $result = $conn->query($query);

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        return $categories;
    }

    // Fetch a single category by ID
    public static function getCategoryById($categoryId)
    {
        $database = new Database();
        $conn = $database->connect();

        // SQL query to fetch a category by its ID
        $query = "SELECT id, name FROM categories WHERE id = $categoryId";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $categoryId); // Bind the category ID to the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if category exists
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();  // Return the category details
        } else {
            return null;  // Return null if no category found
        }
    }
}
