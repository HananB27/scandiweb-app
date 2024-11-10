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
        // Set script execution time limit
        set_time_limit(60); // Temporarily increase the execution time limit to 60 seconds



        // Enable strict error reporting for MySQLi and set connection timeout
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->conn = new \mysqli();
        $this->conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5); // Set a 5-second timeout

        // Attempt to connect
        try {
            $this->conn->real_connect($this->host, $this->username, $this->password, $this->db_name, $this->port);

            // Check if the connection was successful
            if ($this->conn->connect_error) {
                die("Connection Error: " . $this->conn->connect_error);
            }

            echo "Connection successful!";
            return $this->conn;
        } catch (\mysqli_sql_exception $e) {
            // Gracefully handle connection errors
            die("Connection failed: " . $e->getMessage());
        }
    }
}
