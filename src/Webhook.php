<?php
namespace Utrust;

use Utrust\Resources\Event;

class Webhook
{
    private $payload = '';
    private $orderReference = '';
    private $eventType = '';

    public function __construct($payload)
    {
        $this->payload = \json_decode($payload, true);
    }

    /**
    * Verify the incoming webhook notification to make sure it is legit.
    *
    * @since 4.0.0
    * @version 4.0.0
    * @param string $request_body The request_body payload from UTRUST.
    * @return bool
    */
    public function validate( $webhooksSecret ) {
        // Make sure it's a valid JSON
        if (json_last_error()) {
            throw new \Exception('Exception: Invalid payload provided. No JSON object could be decoded.' . print_r( $this->payload, true ));
        }

        // Make sure it has event type
        if (!isset($this->payload['event_type'])) {
            throw new \Exception('Exception: Invalid payload provided.' . print_r( $this->payload, true ));
        }

        // Get signature from response
        $signatureFromResponse = $this->payload->signature;

        // Removes signature from response
        unset($this->payload->signature);

        // sorts response alphabetically by key
        ksort($this->payload);

        // concat keys and values into one string
        $concatedPayload = array();
        foreach( $this->payload as $key => $value ) {
            if(is_object($value)) {
                foreach ($value as $k => $v) {
                $concatedPayload[] = $key;
                    $concatedPayload[] = $k.$v;
                }
            }
            else {
                $concatedPayload[] = $key.$value;
            }
        }
        $concatedPayload = join('', $concatedPayload);

        // sign string with HMAC SHA256
        $signedPayload = hash_hmac('sha256', $concatedPayload, $webhooksSecret);

        // check if signature is correct
        if ($signatureFromResponse === $signedPayload) {
            return true;
        }
        
        throw new \Exception("Exception: Invalid signature!");
    }
}