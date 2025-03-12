<?php
// Database connection parameters (change these according to your own configuration)
$host = "localhost"; // MySQL server address
$username = "root"; // MySQL username
$password = ""; // MySQL password (empty for local server)
$dbname = "subscriptions"; // Name of the database we want to create

// Connecting to MySQL server
$conn = new mysqli($host, $username, $password);

// Checking if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // If there is an error, stop and show the error message
}

// Create the database 'subscriptions' if  does not exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname"; // SQL query to create the database
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' created or already exists.\n"; // If the query is successful, show this message
} else {
    echo "Error creating database: " . $conn->error . "\n"; // If there is an error, show the error message
}

// Select the database to use for creating tables
$conn->select_db($dbname); // We need to specify which database to work with

// SQL query to create the 'clients' table
$create_clients_table = "
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(255) NOT NULL, 
    email VARCHAR(255) NOT NULL UNIQUE, 
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL, 
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);";

// SQL query to create the 'products' table
$create_products_table = "
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(255) NOT NULL, 
    price DECIMAL(10, 2) NOT NULL, 
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

// SQL query to create the 'subscritions' table
$create_subscriptions_table = "
CREATE TABLE IF NOT EXISTS subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    client_id INT, 
    product_id INT,
    start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    finish_date TIMESTAMP, 
    FOREIGN KEY (client_id) REFERENCES clients(id), 
    FOREIGN KEY (product_id) REFERENCES products(id) 
);";

// Execute the query to create the 'clients' table
if ($conn->query($create_clients_table) === TRUE) {
    echo "Table 'clients' created successfully.\n"; 
} else {
    echo "Error creating table 'clients': " . $conn->error . "\n"; 
}

// Execute the query to create the 'products' table
if ($conn->query($create_products_table) === TRUE) {
    echo "Table 'produits' created successfully.\n";
} else {
    echo "Error creating table 'produits': " . $conn->error . "\n"; 
}

// Execute the query to create the 'subscritions' table
if ($conn->query($create_subscriptions_table) === TRUE) {
    echo "Table 'subscriptions' created successfully.\n"; //
} else {
    echo "Error creating table 'subscritions': " . $conn->error . "\n"; //  show the error message
}

// Close the connection to the database after all creations
$conn->close();
?>
