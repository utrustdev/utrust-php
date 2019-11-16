<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

use Utrust\Webhook;

// Load the env var WEBHOOKS_SECRET (using phpdotenv package)
$webhooksSecret = getenv('WEBHOOKS_SECRET');

// The payload should come from something like:
// $payload = file_get_contents( 'php://input' );
// But for demo purposes, we will hardcode an example payload that Utrust can send
$payload = '{ \"event_type\": \"ORDER.PAYMENT.RECEIVED\", \"resource\": { \"amount\": \"10.00\", \"currency\": \"EUR\", \"reference\": \"6257\" }, \"signature\": \"04e33f5a2c85f8781e28da9b21b1cdcf08fd3965f68d2260e76ca559ced41c79\", \"state\": \"completed\" }';

try {
    $webhook = new Webhook($payload);
    $webhook->validate($webhooksSecret);
    http_response_code(200); // Don't delete this

    // Here you can change your Order status
    echo print_r('Successully validated payload with order reference %s and type %s', $webhook->orderReference, $webhook->eventType);
} catch (\Exception $exception) {
    http_response_code(500); // Don't delete this

    // Handle webhook error
    echo 'Error: ' . $exception->getMessage();
}
