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
        public function execute($method, $endpoint, array $body = [])
        {
            // Check the cURL handle has not already been initiated
            if ($this->curl_handle === null) {

                // Initiate the cURL handle
                $this->curl_handle = curl_init();

                // Set initial options
                curl_setopt_array( $this->curl_handle, array(
                        CURLOPT_URL => $this->api_url . $endpoint,
                        CURLOPT_CUSTOMREQUEST => $method,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
                    )
                );
            }

            // Set API_KEY header for cURL
            curl_setopt($this->curl_handle, CURLOPT_HTTPHEADER, array( "Authorization: Bearer $api_key", "Content-Type: application/json"));

            // Set HTTP POST fields for cURL
            curl_setopt($this->curl_handle, CURLOPT_POSTFIELDS, json_encode( $body ) );

            // Execute the cURL session
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