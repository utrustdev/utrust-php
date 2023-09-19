<?php
namespace Utrust;

const SANDBOX_URL = 'https://merchants.api.sandbox.crypto.xmoney.com/api/';
const PRODUCTION_URL = 'https://merchants.api.crypto.xmoney.com/api/';

class ApiClient
{
    private $apiKey;
    private $apiUrl;
    private $curlHandle;

    public function __construct($apiKey, $environment = 'production')
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = ($environment == 'production') ? PRODUCTION_URL : SANDBOX_URL;
        $this->curlHandle = null;
    }

    public function __destruct()
    {
        if ($this->curlHandle !== null) {
            curl_close($this->curlHandle);
        }
    }

    /**
     * Executes a POST cURL request to the Utrust API.
     *
     * @param string $method The API method to call.
     * @param array $body The required and optional fields to pass with the method.
     *
     * @return array Result with the api response.
     */
    private function post($endpoint, array $body = [])
    {
        // Check the cURL handle has not already been initiated
        if ($this->curlHandle === null) {
            // Initiate cURL
            $this->curlHandle = curl_init();

            // Set options
            curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->curlHandle, CURLOPT_MAXREDIRS, 10);
            curl_setopt($this->curlHandle, CURLOPT_TIMEOUT, 30);
            curl_setopt($this->curlHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($this->curlHandle, CURLOPT_POST, 1);
        }

        // Set headers
        $headers = array();
        $headers[] = 'Authorization: Bearer ' . $this->apiKey;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, $headers);

        // Set URL
        curl_setopt($this->curlHandle, CURLOPT_URL, $this->apiUrl . 'stores/orders/');

        // Set body
        curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, json_encode($body));

        // Execute cURL
        $response = curl_exec($this->curlHandle);

        // Check the response of the cURL session
        if ($response !== false) {
            $result = false;

            // Prepare JSON result to object stdClass
            $decoded = json_decode($response);

            // Check the json decoding and set an error in the result if it failed
            if (!empty($decoded)) {
                $result = $decoded;
            } else {
                $result = ['error' => 'Unable to parse JSON result (' . json_last_error() . ')'];
            }
        } else {
            // Returns the error if the response of the cURL session is false
            $result = ['errors' => 'cURL error: ' . curl_error($this->curlHandle)];
        }

        return $result;
    }

    /**
     * Creates a Order.
     *
     * @param object $order The Order object.
     * @param object $customer The Customer object.
     *
     * @return string|object Response data.
     * @throws Exception
     */
    public function createOrder($orderData, $customerData)
    {
        // Build body
        $body = [
            'data' => [
                'type' => 'orders',
                'attributes' => [
                    'order' => $orderData,
                    'customer' => $customerData,
                ],
            ],
        ];

        $response = $this->post('stores/orders', $body);

        if (isset($response->errors)) {
            throw new \Exception('Exception: Request Error! ' . print_r($response->errors, true));
        } elseif (!isset($response->data->attributes->redirect_url)) {
            throw new \Exception('Exception: Missing redirect_url!');
        }

        return $response->data;
    }
}
