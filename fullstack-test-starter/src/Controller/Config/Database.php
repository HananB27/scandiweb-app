<?php

namespace App\Controller\Config;
require_once __DIR__ . "/../../../vendor/autoload.php";

class Database {
    private $host = 'junction.proxy.rlwy.net';
    private $db_name = 'railway';
    private $username = 'root';
    private $password = 'lHdglQmIYwWFBDpNvtLqABFfbBcUHVmU';
    private $port = 49510;
    private $conn;

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
