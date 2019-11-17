<?php
namespace Utrust;

class Webhook
{
    /**
     * Verify the incoming webhook notification to make sure it is legit.
     *
     * @since 4.0.0
     * @version 4.0.0
     * @param string $request_body The request_body payload from UTRUST.
     * @return bool
     */
    public function validateEvent($event, $webhooksSecret)
    {
        $payload = clone $event->getPayload();

        // Removes signature from response
        unset($payload->signature);

        // Concat keys and values into one string
        $concatedPayload = array();
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
        $concatedPayload = join('', $concatedPayload);

        // Sign string with HMAC SHA256
        $signedPayload = hash_hmac('sha256', $concatedPayload, $webhooksSecret);

        // Check if signature is correct
        if ($event->getSignature() === $signedPayload) {
            return true;
        }

        throw new \Exception('Exception: Invalid signature!');
    }
}
