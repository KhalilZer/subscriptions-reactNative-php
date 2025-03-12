<?php
class Database {
    private $host = "localhost"; // Replace if necessary with the host of your database
    private $db_name = "subscriptions"; // The name of the database you are connecting to
    private $username = "root"; // Replace with your MySQL username, if necessary
    private $password = ""; // Replace with your MySQL password if required
    public $conn;

    // Function to establish and return a database connection
    public function getConnection() {
        $this->conn = null;
        try {
            // Attempt to create a new PDO connection
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            // If connection fails, display the error message
            die("Connection error: " . $exception->getMessage());
        }
        // Return the database connection
        return $this->conn;
    }
}
?>
