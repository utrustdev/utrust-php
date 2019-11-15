<?php
    require("src/ApiClient.php");
    require("src/Resources/Customer.php");
    require("src/Resources/Order.php");

    use Utrust\ApiClient;
    use Utrust\Resources\Customer;
    use Utrust\Resources\Order;

    // Api key for Utrust Test store (sandbox)
    const API_KEY = 'u_test_api_cfda5805-2973-4e86-bd91-d3f042a4bf9d';

    // Init Utrust API
    $utrust_api = new ApiClient(API_KEY, 'sandbox');

    // Build Order object
    $order = new Order([
        'reference' => 'REF-12345678',
        'amount' => [
            'total' => '0.99',
            'currency' => 'EUR',
        ],
        'return_urls' => [
            'return_url'  => 'http://example.com/order_success',
            'cancel_url' => 'http://example.com/order_canceled',
            'callback_url' => 'http://example.com/webhook_url'
        ],
        'line_items' => [
            [
                'sku' => 'tshirt-1234',
                'name' => 'T-shirt',
                'price' => '10.00',
                'currency' => 'EUR',
                'quantity' => 1
            ]
        ]
    ]);

    // Build Customer object
    $customer = new Customer([
        'email' => 'daniel+php@utrust.com',
    ]);

    // Make the API request
    $redirect_url = $utrust_api->create_order($order, $customer);

    // Use the $redirect_url to redirect the customer 
    echo $redirect_url;


