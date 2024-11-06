<?php

namespace App\Controller\Models;
require_once __DIR__  . "/../../../vendor/autoload.php";

use App\Controller\Config\Database;

class Price
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Fetch all prices
    public static function getAllPrices()
    {
        $database = new Database();
        $conn = $database->connect();

        $query = "SELECT * FROM prices";
        $result = $conn->query($query);

        $prices = [];
        while ($row = $result->fetch_assoc()) {
            $prices[] = $row;
        }

        return $prices;
    }

    // Fetch a single price by product ID
    public static function getPriceByProductId($productId)
    {
        $database = new Database();
        $conn = $database->connect();

        // Fetching price details based on product ID
        $query = "SELECT amount, currency_label, currency_symbol FROM prices WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $productId);  // Bind the product ID to the query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();  // Return all price details as an associative array
        } else {
            return null;
        }
    }

    public static function getCurrencyById($currencyId) {
        $database = new Database();
        $conn = $database->connect();
    
        $query = "SELECT label, symbol, __typename FROM currency WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $currencyId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $currency = $result->fetch_assoc();
        if ($currency) {
            return $currency;
        } else {
            error_log("No currency found for currency ID: " . $currencyId);
            return null;
        }
    }
    
}
