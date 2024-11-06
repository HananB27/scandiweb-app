<?php

namespace App\Controller\Config;
require_once __DIR__ . "/../../../vendor/autoload.php";

use Dotenv\Dotenv;

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    private $conn;

    public function __construct() {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();

        // Set database connection parameters from environment variables
        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->port = $_ENV['DB_PORT'];
    }

    public function connect() {
        // Initialize the connection with the specified port
        $this->conn = new \mysqli($this->host, $this->username, $this->password, $this->db_name, $this->port);

        // Check if the connection was successful
        if ($this->conn->connect_error) {
            die("Connection Error: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
