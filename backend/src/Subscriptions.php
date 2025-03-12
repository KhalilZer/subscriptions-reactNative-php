<?php
// Include necessary files
require_once "../src/Database.php"; // Include the database connection class
require_once "../src/Clients.php";  // Include the clients class

class Subscriptions {

    private $conn;
    private $clients;

    // Constructor to initialize the database connection and Clients instance
    public function __construct() {
        // Initialize database connection
        $database = new Database();
        $this->conn = $database->getConnection();
        // Initialize the Clients class
        $this->clients = new Clients();
    }

    // Function to retrieve subscriptions of a user (limited to 3 products)
    public function getUserSubscriptions($client_id) {
    
        // SQL query to retrieve products associated with the user's subscriptions (limited to 3 products for now)
        $query = "
            SELECT p.id AS product_id, p.name AS product_name, p.price, p.date_creation, s.start_date, s.finish_date 
            FROM subscriptions s 
            JOIN products p ON s.product_id = p.id 
            WHERE s.client_id = :client_id 
            LIMIT 3;
        ";

        // Prepare and execute the query
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->execute();

        // Check if any products are returned from the query
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return the details of the products associated with the subscriptions
        } else {
            return []; // Return an empty array if no products are found
        }
    }
}
?>
