<?php

use PHPUnit\Framework\TestCase;
use Utrust\Webhook\Event;

class WebhookTest extends TestCase
{
    public function testInvalidPayloadJSON(): void
    {
        $invalidPayload = "[ this is not a JSON serialized payload ]";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid payload provided.');

        $event = new Event($invalidPayload);
    }

    public function testInvalidPayloadMissingEventType(): void
    {
        $invalidPayload = "{ \"resource\": { \"amount\": \"0.99\", \"currency\": \"EUR\", \"reference\": \"REF-12345678\" }, \"signature\": \"1234-this-is-an-invalid-signature-1234\", \"state\": \"cancelled\" }";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Event_type is missing on the payload.');

        $event = new Event($invalidPayload);
    }

    public function testInvalidPayloadMissingAmount(): void
    {
        $invalidPayload = "{ \"event_type\": \"ORDER.PAYMENT.CANCELLED\", \"resource\": { \"currency\": \"EUR\", \"reference\": \"REF-12345678\" }, \"signature\": \"bb32374545004b5f4a1264a8e8e78e3357e27a35a8a3b334fe1a2a47b60a35ba\", \"state\": \"cancelled\" }";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Amount is missing on the payload.');

        $event = new Event($invalidPayload);
    }

    public function testInvalidPayloadMissingCurrency(): void
    {
        $invalidPayload = "{ \"event_type\": \"ORDER.PAYMENT.CANCELLED\", \"resource\": { \"amount\": \"0.99\", \"reference\": \"REF-12345678\" }, \"signature\": \"bb32374545004b5f4a1264a8e8e78e3357e27a35a8a3b334fe1a2a47b60a35ba\", \"state\": \"cancelled\" }";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Currency is missing on the payload.');

        $event = new Event($invalidPayload);
    }

    public function testInvalidPayloadMissingReference(): void
    {
        $invalidPayload = "{ \"event_type\": \"ORDER.PAYMENT.CANCELLED\", \"resource\": { \"amount\": \"0.99\", \"currency\": \"EUR\" }, \"signature\": \"1234-this-is-an-invalid-signature-1234\", \"state\": \"cancelled\" }";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Reference is missing on the payload.');

        $event = new Event($invalidPayload);
    }

    public function testValidSignature(): void
    {
        $validPayload = "{ \"event_type\": \"ORDER.PAYMENT.CANCELLED\", \"resource\": { \"amount\": \"0.99\", \"currency\": \"EUR\", \"reference\": \"REF-12345678\" }, \"signature\": \"bb32374545004b5f4a1264a8e8e78e3357e27a35a8a3b334fe1a2a47b60a35ba\", \"state\": \"cancelled\" }";

        $event = new Event($validPayload);
        $result = $event->validateSignature('u_test_webhooks_123456789');

        $this->assertTrue($result);
    }

    public function testInvalidSignature(): void
    {
        $invalidPayload = "{ \"event_type\": \"ORDER.PAYMENT.CANCELLED\", \"resource\": { \"amount\": \"0.99\", \"currency\": \"EUR\", \"reference\": \"REF-12345678\" }, \"signature\": \"1234-this-is-an-invalid-signature-1234\", \"state\": \"cancelled\" }";

        $event = new Event($invalidPayload);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid signature!");

        $event->validateSignature('u_test_webhooks_123456789');
    }
}
