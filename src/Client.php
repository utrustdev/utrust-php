<?php
    namespace Utrust;

    const SANDBOX_URL = 'https://merchants.api.sandbox-utrust.com/api/';
    const PRODUCTION = 'production';
    const PRODUCTION_URL = 'https://merchants.api.utrust.com/api/';

    class Client
    {
        private $api_key;
        private $api_url;
        private $curl_handle;

        public function __construct($api_key, $environment = PRODUCTION)
        {
            $this->api_key = $api_key;
            $this->api_url = ( $environment == PRODUCTION ) ? PRODUCTION_URL: SANDBOX_URL;
            $this->curl_handle = null;
        }

        public function __destruct() {
            if ($this->curl_handle !== null) {
                curl_close($this->curl_handle);
            }
        }

        /**
         * Executes a cURL request to the Utrust API.
         *
         * @param string $method The API method to call.
         * @param array $body The required and optional fields to pass with the method.
         *
         * @return array Result data with the success or error message.
         */
        public function post($endpoint, array $body = [])
        {
            // Check the cURL handle has not already been initiated
            if ($this->curl_handle === null) {                
                // Initiate cURL 
                $this->curl_handle = curl_init();
                
                // Set options 
                curl_setopt($this->curl_handle, CURLOPT_URL, $this->api_url . 'stores/orders/');
                curl_setopt($this->curl_handle, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($this->curl_handle, CURLOPT_MAXREDIRS, 10);
                curl_setopt($this->curl_handle, CURLOPT_TIMEOUT, 30);
                curl_setopt($this->curl_handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($this->curl_handle, CURLOPT_POST, 1);
                curl_setopt($this->curl_handle, CURLOPT_POSTFIELDS, "{\n  \"data\": {\n    \"type\": \"orders\",\n    \"attributes\": {\n      \"order\": {\n        \"reference\": \"TEST-by-utrust-using-insomnia-NQWRG24\",\n        \"amount\": {\n          \"total\": \"0.99\",\n          \"currency\": \"EUR\",\n          \"details\": {\n            \"subtotal\": \"0.75\",\n\t\t\t\t\t\t\"tax\": \"0.15\",\n            \"shipping\": \"0.10\",\n\t\t\t\t\t\t\"discount\": \"0.01\"\n          }\n        },\n        \"return_urls\": {\n\t\t\t\t\t\"callback_url\": \"http://www.mocky.io/v2/5cf78c36300000f414a37de9\", \n          \"return_url\": \"http://example.com/return\",\n\t\t\t\t\t\"cancel_url\": \"http://example.com/cancel\"\n        },\n        \"line_items\": [\n\t\t\t\t\t{\n\t\t\t\t\t\t\"sku\": \"AFG1245\",\n\t\t\t\t\t\t\"name\": \"T-shirt\",\n\t\t\t\t\t\t\"price\": \"0.50\",\n\t\t\t\t\t\t\"currency\": \"EUR\",\n\t\t\t\t\t\t\"quantity\": 1\n\t\t\t\t\t},\n\t\t\t\t\t\t\t\t\t\t{\n\t\t\t\t\t\t\"sku\": \"AFG12457\",\n\t\t\t\t\t\t\"name\": \"T-shirt old scool\",\n\t\t\t\t\t\t\"price\": \"0.25\",\n\t\t\t\t\t\t\"currency\": \"EUR\",\n\t\t\t\t\t\t\"quantity\": 1\n\t\t\t\t\t}\n\t\t\t\t]\n      },\n      \"customer\": {\n        \"first_name\": \"Antonio\",\n        \"last_name\": \"Coelho\",\n        \"email\": \"daniel@utrust.com\",\n        \"address1\": \"118 Main St\",\n        \"address2\": \"7th Floor\",\n        \"city\": \"New York\",\n        \"state\": \"New York\",\n        \"postcode\": \"10001\",\n        \"country\": \"US\"\n      }\n    }\n  }\n}");
            }
            
            // Set headers 
            $headers = array();
            $headers[] = 'Authorization: Bearer ' . $this->api_key;
            $headers[] = 'Content-Type: application/json';
            curl_setopt($this->curl_handle, CURLOPT_HTTPHEADER, $headers);

            // Execute cURL 
            $response = curl_exec($this->curl_handle);

            // Check the response of the cURL session
            if ($response !== FALSE) {
                $result = false;

                // Prepare JSON result
                $decoded = json_decode($response);

                // Check the json decoding and set an error in the result if it failed
                if ($decoded !== NULL && count($decoded)) {
                    $result = $decoded;
                } else {
                    $result = ['error' => 'Unable to parse JSON result (' . json_last_error() . ')'];
                }
                
            } else {
                // Throw an error if the response of the cURL session is false
                $result = ['error' => 'cURL error: ' . curl_error($this->curl_handle)];
            }

            return $result;
        }
    }