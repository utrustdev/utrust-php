<?php
    require("src/Client.php");

    use Utrust\Client;

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

    // Build body
    $body = [
        'data' => [
            'type' => 'orders',
            'attributes' => [
                'order' => $order_data,
                'customer' => $customer_data
            ]
        ]
    ];

    // Create Order
    $result = $utrust_client->post('stores/orders', $body );

    if ( isset( $result->data->attributes->redirect_url ) ) {
        echo $result->data->attributes->redirect_url;
    }
    else {
        echo 'No redirect URL!';
    }
    
    // Optimally we want this instead
    // $order = new Utrust\Order(...);
    // $utrust_client = new Client(API_KEY);
    // $response = $utrust_client->createOrder($order);
    // ...


