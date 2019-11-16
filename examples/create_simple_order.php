<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

use Utrust\ApiClient;
use Utrust\Resources\Customer;
use Utrust\Resources\Order;

// Load the env var API_KEY (using phpdotenv package)
$api_key = getenv('API_KEY');

// Init Utrust API
$utrustApi = new ApiClient($api_key, 'sandbox');

// Build Order object
$order = new Order([
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
]);

// Build Customer object
$customer = new Customer([
    'email' => 'daniel+php@utrust.com',
]);

try {
    // Make the API request
    $response = $utrustApi->createOrder($order, $customer);

    // Use the $redirect_url to redirect the customer to our Payment Widget
    echo $response->attributes->redirect_url;
} catch (Exception $e) {
    // Handle error (e.g.: show message to the customer)
    echo 'Something went wrong: ' . $e->getMessage();
}
