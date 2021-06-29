<?php
namespace Utrust\Webhook;

class Event
{
    private $payload = '';

    public function __construct($payload)
    {
        $this->payload = \json_decode($payload);

        // Make sure it's a valid JSON
        if (json_last_error()) {
            throw new \Exception('Invalid payload provided. No JSON object could be decoded.'
                . print_r($this->payload, true));
        }

        // Make sure it has event type
        if (!isset($this->payload->event_type)) {
            throw new \Exception('Event_type is missing on the payload.' . print_r($this->payload, true));
        }

        // Make sure it has amount
        if (!isset($this->payload->resource->amount)) {
            throw new \Exception('Amount is missing on the payload.' . print_r($this->payload, true));
        }

        // Make sure it has currency
        if (!isset($this->payload->resource->currency)) {
            throw new \Exception('Currency is missing on the payload.' . print_r($this->payload, true));
        }

        // Make sure it has reference
        if (!isset($this->payload->resource->reference)) {
            throw new \Exception('Reference is missing on the payload.' . print_r($this->payload, true));
        }
    }

    /**
     * Gets Event Type data
     *
     * @return string Event Type data.
     */
    public function getEventType()
    {
        return $this->payload->event_type;
    }

    /**
     * Gets Order amount
     *
     * @return string Order amount.
     */
    public function getOrderAmount()
    {
        return $this->payload->resource->amount;
    }

    /**
     *
     * Gets Order currency
     * @return string Order currency.
     */
    public function getOrderCurrency()
    {
        return $this->payload->resource->currency;
    }

    /**
     * Gets Order Reference data
     *
     * @return string Order Reference data.
     */
    public function getOrderReference()
    {
        return $this->payload->resource->reference;
    }

    /**
     * Gets Signature data
     *
     * @return string Signature data.
     */
    public function getSignature()
    {
        return $this->payload->signature;
    }

    /**
     * Gets Payload data
     *
     * @return string Payload data.
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Verify the incoming webhook notification to make sure it is legit.
     *
     * @param string $webhooksSecret The Webhooks Secret from Utrust Merchant dashboard.
     *
     * @return bool
     * @throws Exception
     */
    public function validateSignature($webhooksSecret)
    {
        if ($webhooksSecret == null) {
            throw new \Exception('Webhooks Secret cant be NULL!');
        }

        $payload = clone $this->payload;

        // Removes signature from response
        unset($payload->signature);

        // Concat keys and values into one string
        $concatedPayload = [];
        foreach ($payload as $key => $value) {
            if (is_object($value)) {
                foreach ($value as $k => $v) {
                    $concatedPayload[] = $key;
                    $concatedPayload[] = $k . $v;
                }
            } else {
                $concatedPayload[] = $key . $value;
            }
        }
        // Sort array alphabetically
        ksort($concatedPayload);
        // Concat the array
        $concatedPayload = join('', $concatedPayload);
        // Sign string with HMAC SHA256
        $signedPayload = hash_hmac('sha256', $concatedPayload, $webhooksSecret);

        // Check if signature is correct
        if ($this->getSignature() === $signedPayload) {
            return true;
        }

        throw new \Exception('Invalid signature!');
    }
}
