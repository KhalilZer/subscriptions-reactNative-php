<?php
// Allow CORS for all domains and set content type to JSON
header("Access-Control-Allow-Origin: *"); // Autoriser tous les domaines
header("Access-Control-Allow-Credentials: true"); // Autoriser les cookies (sessions)
header("Content-Type: application/json; charset=UTF-8");

// Include the required classes for database connection and user management
require_once "../src/Database.php";
require_once "../src/Clients.php";
require_once "../src/Subscriptions.php";

// Create a Database object and establish a connection
$database = new Database();
$conn = $database->getConnection();

// Create a Clients object for handling user-related actions
$clients = new Clients();

// Get the HTTP request method (GET, POST, etc.)
$request_method = $_SERVER["REQUEST_METHOD"];

// Handle different request methods using switch-case
switch ($request_method) {
    // Handle GET requests (e.g., testing API functionality)
    case 'GET':
        if (isset($_GET["action"]) && $_GET["action"] == "test") {
            // Return a simple message to confirm API functionality
            echo json_encode(["message" => "API is working!"]);
        }


        if (isset($_GET['action']) && $_GET['action'] == 'getSubscriptionsByUser') {
            

          
                session_start();
                 
                if (isset($_GET['client_id'])) {
                    $client_id = $_GET['client_id'];
                    $subscriptions = new Subscriptions();
                    $userSubscriptions = $subscriptions->getUserSubscriptions($client_id);
                    echo json_encode(["subscriptions" => $userSubscriptions]);
                } else {
                    echo json_encode(["error" => "Utilisateur non connecté"]);
                }
            }
        // Handle checkLogin request
       if (isset($_GET['action']) && $_GET['action'] == 'checkLogin') {
            // Appel de la fonction checkLogin() pour vérifier si l'utilisateur est connecté
            $response = $clients->checkLogin();
            echo $response; // On retourne directement la réponse JSON
        }
        break;

    // Handle POST requests for user registration, login, and verification
    case 'POST':
        // User registration
        if (isset($_GET['action']) && $_GET['action'] == 'register') {
            // Decode the JSON input data
            $data = json_decode(file_get_contents("php://input"));
            // Check if all required fields are provided
            if (isset($data->name, $data->email, $data->phone, $data->password)) {
                // Register the user in the database
                $response = $clients->register($data->name, $data->email, $data->phone, $data->password);
            } else {
                // Return an error if data is missing
                $response = ["error" => "Missing required fields"];
            }
            // Send response as JSON
            echo json_encode($response);
        }

        // User login
        if (isset($_GET['action']) && $_GET['action'] == 'login') {
            // Decode the JSON input data
            $data = json_decode(file_get_contents("php://input"));
            // Check if email and password are provided
            if (isset($data->email, $data->password)) {
                // Validate user credentials
                $response = $clients->login($data->email, $data->password);
            } else {
                // Return an error if credentials are missing
                $response = ["error" => "Missing email or password"];
            }
            // Send response as JSON
            echo json_encode($response);
        }

          // Handle logout request (destroy the session)
        if (isset($_GET['action']) && $_GET['action'] == 'logout') {
            session_start(); // Start the session to access session variables
            if (isset($_SESSION['user_id'])) {
                // Destroy the session to log out the user
                session_unset(); // Unset all session variables
                session_destroy(); // Destroy the session
                echo json_encode(["message" => "Logout successful"]);
            } else {
                echo json_encode(["error" => "No active session found"]);
            }
        }

        
        // Verify the authentication code
        if (isset($_GET['action']) && $_GET['action'] == 'verify') {
            // Decode the JSON input data
            $data = json_decode(file_get_contents("php://input"));
            // Check if the verification code is provided
            if (isset($data->code)) {
                // Call the verify function to check the code
                $response = $clients->verifyCode($data->code);
            } else {
                // Return an error if the code is missing
                $response = ["error" => "Verification code is required"];
            }
            // Send response as JSON
            echo json_encode($response);
        }

        // Handle updateUserInfo request (update user information)
        if (isset($_GET['action']) && $_GET['action'] == 'updateUserInfo') {
            session_start();
            if (isset($_SESSION['user_id'])) {
                // Decode the JSON input data
                $data = json_decode(file_get_contents("php://input"));
                // Check if all required fields are provided
                if (isset($data->name, $data->email, $data->phone)) {
                    // Update user information in the database
                    $user_id = $_SESSION['user_id'];
                    $response = $clients->updateUserInfo($user_id, $data->name, $data->email, $data->phone);
                } else {
                    $response = ["error" => "Missing required fields"];
                }
                echo json_encode($response);
            } else {
                echo json_encode(["error" => "Utilisateur non connecté"]);
            }
        }
        break;

    // Default case for unsupported HTTP methods
    default:
        // Return an error message for unsupported methods
        echo json_encode(["error" => "Unsupported request method"]);
        break;
}
?>
