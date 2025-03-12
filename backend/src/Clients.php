<?php
require_once "Database.php";
require_once "../config/whapi_config_verification.php";  // Ensure the WhatsApp service for sending the code is included

//import all WHAPI Classes
use OpenAPI\Client\Api\MessagesApi;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\Model\SenderText;
use GuzzleHttp\Client;

class Clients {
    private $conn;
    private $apiInstance;

    public function __construct() {
        // Initialize the database connection
        $database = new Database();
        $this->conn = $database->getConnection();

        // Configuration of the WhatsApp API (token included directly)
        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('token', "cmpqpWvrpRSAWdyRwLh1eAmFbkdUdwPR");  // Your API token here (My own Token) 

        // Initialize the WhatsApp API instance
        $this->apiInstance = new MessagesApi(new Client(), $config);  // Initialize the WhatsApp API
    }

    // Registration function
    public function register($name, $email, $phone, $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);  // Hash the password for storage
        $my_query = "INSERT INTO clients (name, email, phone, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($my_query);
        if ($stmt->execute([$name, $email, $phone, $hashed_password])) {
            return ["message" => "User created successfully!"];
        }
        return ["error" => "Error during registration"];
    }

    // Login function
    public function login($email, $password) {

        // Check credentials in the database
        $my_query = "SELECT * FROM clients WHERE email = ?";
        $stmt = $this->conn->prepare($my_query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // If login is successful, send a verification message via WhatsApp
            $phoneNumber = $user['phone'];  // Use the client's phone number
            $verificationCode = rand(100000, 999999);  // Generate a random verification code

            /* $verificationCode = 123456;  // Hardcoded verification code for testing */

            session_start();

            // Save the verification code in the session
            $_SESSION['verification_code'] = $verificationCode;
            $_SESSION['verification_code_time'] = time();  // Save the time of the code (for expiration if necessary)
            

            // Save the user ID for login check
            $_SESSION['user_id'] = $user['id'];

            // Send the verification message via WhatsApp
            $this->sendVerificationMessage($phoneNumber, $verificationCode);

            return json_encode([
                "message" => "Connection success! Verification code sent to WhatsApp",
                "user" => $user,
                "code" => $verificationCode
            ]);
        }

        return ["error" => "Email or Password incorrect"];
    }

    // Function to check if the user is logged in
    public function checkLogin() {
        session_start();

        // Check if the user is authenticated
        if (isset($_SESSION['user_id'])) {
                // Retrieve the user from the database using their ID
                $user_id = $_SESSION['user_id'];
                $my_query = "SELECT * FROM clients WHERE id = ?";
                $stmt = $this->conn->prepare($my_query);
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // If the user exists, return their information
                if ($user) {
                    return json_encode([
                        "user" => $user // Return the full user object here
                    ]);
                } else {
                    return json_encode(["error" => "User not found"]);
                }
            }

            // If the user is not logged in, return an error message
            return json_encode(["error" => "User not logged in"]);
    }

    // Function to verify the entered verification code
    public function verifyCode($enteredCode) {
        session_start();  

        // Check if the code is correct and not expired
        if (isset($_SESSION['verification_code']) && $_SESSION['verification_code'] == $enteredCode) {
            // If the code is correct, check if it is not expired
            if ((time() - $_SESSION['verification_code_time']) <= 1200) {  // The code expires after 20 minutes
             
                unset($_SESSION['verification_code']);  // Remove the code from the session once validated
                unset($_SESSION['verification_code_time']);  // Remove the time of the code

                return ["message" => "Code verified successfully!"];
            } else {
                return ["error" => "Verification code expired."];
            }
        }

        return ["error" => "Invalid verification code."];
    }

    // Function to send the verification message via WhatsApp
    public function sendVerificationMessage($phoneNumber, $verificationCode) {
        // Create a text message object
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);  // Remove anything except numbers

        $sender_text = new SenderText();
        $sender_text->setTo($phoneNumber);  // Include the country code, for example '13016789891'
        $sender_text->setBody("Your verification code is  $verificationCode");

        // Send the message via the API
        try {
            $result = $this->apiInstance->sendMessageText($sender_text); // Send the message
            print_r($result);  // Print the response (useful for debugging)
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();  // Handle any errors
        }
    }

    // Function to update the user's information
    public function updateUserInfo($user_id, $name, $email, $phone) {
        session_start();

        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
            // Update the data in the "clients" table
            $query = "UPDATE clients SET name = ?, email = ?, phone = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$name, $email, $phone, $user_id]);

            return json_encode(["message" => "Data updated successfully"]);
        }

        return json_encode(["error" => "User not logged in or invalid ID"]);
    }
}
?>
