<?php
require_once './vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

use Utrust\Webhook\Event;

// Load the env var WEBHOOKS_SECRET (using phpdotenv package)
$webhooksSecret = getenv('WEBHOOKS_SECRET');

// The payload should come from something like:
// $payload = file_get_contents( 'php://input' );
// But for demo purposes, we will hardcode an example payload that Utrust can send
$payload = "{ \"event_type\": \"ORDER.PAYMENT.CANCELLED\", \"resource\": { \"amount\": \"0.99\", \"currency\": \"EUR\", \"reference\": \"REF-12345678\" }, \"signature\": \"47215d0f4737341f4f1f5fb947b5ebb16af71c1d701800b2ab869890d0ac1c27\", \"state\": \"cancelled\" }";

try {
    $event = new Event($payload);
    $event->validateSignature($webhooksSecret);
    http_response_code(200); // Don't delete this

    // Here you can change your Order status
    echo sprintf('Successully validated payload with order reference %s and type %s', $event->getOrderReference(), $event->getEventType());
} catch (\Exception $exception) {
    http_response_code(400); // Don't delete this

    // Handle webhook error
    echo 'Error: ' . $exception->getMessage();
}
