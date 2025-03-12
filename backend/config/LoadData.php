<?php
// Include the database connection file
require_once '../src/Database.php';

class DataLoad {
    private $conn;

    public function __construct() {
        // Initialize the database connection
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Function to insert data into the 'clients' table
    public function loadClientsData() {
        $query = "
            INSERT INTO clients (name, email, phone, password) 
            VALUES 
                ('Jean Dupont', 'jeandupont@example.com', '+33123456789', 'hashed_password_1'),
                ('Marie Curie', 'mariecurie@example.com', '+33612345678', 'hashed_password_2'),
                ('Khalil Zerzour', 'khalilzerzour@example.com', '+33698765432', 'hashed_password_3');
        ";

        // Execute the query
        if ($this->conn->exec($query)) {
            echo "Data successfully inserted into the 'clients' table.\n";
        } else {
            echo "Error inserting data into the 'clients' table.\n";
        }
    }

    // Function to insert data into the 'products' table
    public function loadProductsData() {
        $query = "
            INSERT INTO products (name, price) 
            VALUES 
                ('Produit 1', 29.99),
                ('Produit 2', 49.99),
                ('Produit 3', 99.99);
        ";

        // Execute the query
        if ($this->conn->exec($query)) {
            echo "Data successfully inserted into the 'products' table.\n";
        } else {
            echo "Error inserting data into the 'products' table.\n";
        }
    }

    // Function to insert data into the 'subscriptions' table
    public function loadSubscriptionsData() {
        $query = "
            INSERT INTO subscriptions (client_id, product_id, finish_date) 
            VALUES 
                (1, 1, '2025-12-31'),
                (1, 2, '2025-06-30'),
                (1, 3, '2025-06-23'),
                (2, 3, '2025-12-31'),
                (3, 1, '2025-12-31');
        ";

        // Execute the query
        if ($this->conn->exec($query)) {
            echo "Data successfully inserted into the 'subscriptions' table.\n";
        } else {
            echo "Error inserting data into the 'subscriptions' table.\n";
        }
    }
}

// Instantiate the DataLoad class and call the methods to load the data
$dataload = new DataLoad();
$dataload->loadClientsData();
$dataload->loadProductsData();
$dataload->loadSubscriptionsData();
?>
