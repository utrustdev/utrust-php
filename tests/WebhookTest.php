<?php

use PHPUnit\Framework\TestCase;
use Utrust\Webhook\Event;

class WebhookTest extends TestCase
{
    public function testValidSignature(): void
    {
        $validPayload = "{ \"event_type\": \"ORDER.PAYMENT.CANCELLED\", \"resource\": { \"amount\": \"0.99\", \"currency\": \"EUR\", \"reference\": \"REF-12345678\" }, \"signature\": \"bb32374545004b5f4a1264a8e8e78e3357e27a35a8a3b334fe1a2a47b60a35ba%\", \"state\": \"cancelled\" }";

        $event = new Event($validPayload);
        $result = $event->validateSignature('u_test_webhooks_123456789');

        $this->assertTrue($result);
    }

    public function testInvalidSignature(): void
    {
        $invalidPayload = "{ \"event_type\": \"ORDER.PAYMENT.CANCELLED\", \"resource\": { \"amount\": \"0.99\", \"currency\": \"EUR\", \"reference\": \"REF-12345678\" }, \"signature\": \"1234-this-is-an-invalid-signature-1234\", \"state\": \"cancelled\" }";

        $event = new Event($invalidPayload);

        $this->expectExceptionMessage("Invalid signature!");
        $event->validateSignature('u_test_webhooks_123456789');
    }
}
