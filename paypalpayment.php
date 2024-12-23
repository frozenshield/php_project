
<?php

// Include Composer's autoloader
require 'vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access the environment variables
$clientId = $_ENV['PAYPAL_CLIENT_ID'];
$clientSecret = $_ENV['PAYPAL_CLIENT_SECRET'];

echo "Client ID: $clientId\n";


?>