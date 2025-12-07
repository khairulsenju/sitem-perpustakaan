<?php

namespace Arya\SistemPerpustakaan\Core;

use Arya\SistemPerpustakaan\Config\Database;

class Model {
    protected $db;
    
    public function __construct() {
        // Database connection
        $host = Database::getHost();
        $dbname = Database::getDatabaseName();
        $username = Database::getUsername();
        $password = Database::getPassword();
        
        try {
            // Add timeout and other connection options
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                \PDO::ATTR_TIMEOUT => 30, // 30 second timeout
            ];
            
            $this->db = new \PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
        } catch (\PDOException $e) {
            // Log the error for debugging
            error_log("Database connection failed: " . $e->getMessage());
            
            // For development, show a more detailed error
            if (defined('DEBUG') && DEBUG) {
                throw new \Exception("Database connection failed: " . $e->getMessage());
            } else {
                // For production, show a generic error
                throw new \Exception("Unable to connect to the database. Please check the configuration.");
            }
        }
    }
}