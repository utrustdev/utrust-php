<?php
    require("src/Client.php");
    require("src/Order.php");

    use Utrust\Client;
    use Utrust\Order;

    // Api key for Utrust Test store (sandbox)
    const API_KEY = 'u_test_api_cfda5805-2973-4e86-bd91-d3f042a4bf9d';

    // Init Utrust API
    $utrust_client = new Client(API_KEY, 'sandbox');

    // Build order data
    $order_data = [
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
    ];

    // Build customer data
    $customer_data = [
        'email' => 'daniel+php@utrust.com',
    ];

    // Create Order object
    $order = new Order($order_data, $customer_data);

    // Make the API request
    $redirect_url = $utrust_client->create_order($order);

    // Use the $redirect_url to redirect the customer 
    echo $redirect_url;


