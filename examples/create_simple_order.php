<?php
require_once './vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

use Utrust\ApiClient;
use Utrust\Validator;

// Load the env var API_KEY (using phpdotenv package)
$api_key = getenv('API_KEY');

// Init Utrust API
$utrustApi = new ApiClient($api_key, 'sandbox');

// Build Order data array
$orderData = [
    'reference' => 'REF-12345678',
    'amount' => [
        'total' => '0.99',
        'currency' => 'EUR',
    ],
    'return_urls' => [
        'return_url' => 'http://example.com/order_success',
        'cancel_url' => 'http://example.com/order_canceled',
        'callback_url' => 'http://example.com/webhook_url',
    ],
    'line_items' => [
        [
            'sku' => 'tshirt-1234',
            'name' => 'T-shirt',
            'price' => '10.00',
            'currency' => 'EUR',
            'quantity' => 1,
        ],
    ],
];

// Build Customer data array
$customerData = [
    'first_name' => 'Daniel',
    'last_name' => 'Coelho',
    'email' => 'daniel+php@utrust.com',
    'country' => 'PT',
];

try {
    // Validate data
    Validator::order($orderData);
    Validator::customer($customerData);

    // Make the API request
    //$response = $utrustApi->createOrder($orderData, $customerData);

    // Use the $redirect_url to redirect the customer to our Payment Widget
    echo $response->attributes->redirect_url;
} catch (Exception $e) {
    // Handle error (e.g.: show message to the customer)
    echo 'Something went wrong: ' . $e->getMessage();
}
