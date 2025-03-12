<?php

// Include the Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';  // Make sure to use the correct path for your project

// Use the necessary classes for the WhatsApp API
use OpenAPI\Client\Api\MessagesApi;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\Model\SenderText;
use GuzzleHttp\Client;

// API configuration with your token
$config = Configuration::getDefaultConfiguration()
    ->setApiKey('token', 'bearerAuth: 4EZ7XWCd2QTP2PmL6wC3oZr2tuFH8jzt');  // Replace with your actual API token

// Initialize the API instance with Guzzle
$apiInstance = new MessagesApi(
    new Client(),
    $config
);

?>
