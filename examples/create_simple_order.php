<?php
    // Api key for Utrust Test store (sandbox)
    const API_KEY = 'u_test_api_5541f83d-6550-4a05-b34e-617445d2682d';

    require_once __DIR__ . "/vendor/autoload.php";

    use Utrust\ApiRequest;

    // Init Utrust API
    $utrust_api = new ApiRequest(API_KEY, 'sandbox');

    // Build order data
    $order_data = array (
        'reference' => 'REF-12345678',
        'amount' => array(
            'total' => '0.99',
            'currency' => 'EUR',
        ),
        'return_urls' => array(
            'return_url'  => 'http://example.com/order_success',
            'cancel_url' => 'http://example.com/order_canceled',
            'callback_url' => 'http://example.com/webhook_url'
        ),
    );

    // Build customer data
    $customer_data = array (
        'email' => 'daniel+php@utrust.com',
    );

    // Build body
    $body = array(
        'data' =>	array(
            'type' => 'orders',
            'attributes' => array(
                'order' => $order_data,
                'customer' => $customer_data
            )
        )
    );

    // Create Order
    $result = $api->execute('post', 'stores/orders', $body );

    if ( isset( $result->data->attributes->redirect_url ) ) {
        echo $result->data->attributes->redirect_url;
    }
    else {
        echo 'No redirect URL!';
    }

    echo 'END';
?>