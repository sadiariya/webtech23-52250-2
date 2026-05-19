<?php
// config/database.php
class Database {
    private $host = "localhost";
    private $db_name = "hotelbookingsystem";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            die("Database connection error: " . $e->getMessage());
        }
        return $this->conn;
    }
}

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in and has housekeeping role
function isHousekeeping() {
    return isset($_SESSION['user_id']) && 
           isset($_SESSION['role']) && 
           $_SESSION['role'] === 'housekeeping';
}

// Redirect if not housekeeping
function requireHousekeeping() {
    if (!isHousekeeping()) {
        header("Location: index.php?controller=auth&action=login");
        exit();
    }
}
?>