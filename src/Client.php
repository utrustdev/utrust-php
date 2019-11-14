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
         * Executes a POST cURL request to the Utrust API.
         *
         * @param string $method The API method to call.
         * @param array $body The required and optional fields to pass with the method.
         *
         * @return array Result data with the success or error message.
         */
        private function post($endpoint, array $body = [])
        {
            // Check the cURL handle has not already been initiated
            if ($this->curl_handle === null) {                
                // Initiate cURL 
                $this->curl_handle = curl_init();
                
                // Set options 
                curl_setopt($this->curl_handle, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($this->curl_handle, CURLOPT_MAXREDIRS, 10);
                curl_setopt($this->curl_handle, CURLOPT_TIMEOUT, 30);
                curl_setopt($this->curl_handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($this->curl_handle, CURLOPT_POST, 1);
            }
            
            // Set headers 
            $headers = array();
            $headers[] = 'Authorization: Bearer ' . $this->api_key;
            $headers[] = 'Content-Type: application/json';
            curl_setopt($this->curl_handle, CURLOPT_HTTPHEADER, $headers);

            // Set URL
            curl_setopt($this->curl_handle, CURLOPT_URL, $this->api_url . 'stores/orders/');

            // Set body
            curl_setopt($this->curl_handle, CURLOPT_POSTFIELDS, json_encode( $body ));

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

        /**
         * Creates a Order.
         *
         * @param array $order The Order object.
         * @param array $customer_data The array with the Customer data.
         *
         * @return string Result data with the redirect URL or error message.
         */
        public function create_order($order) 
        {
            $order_data = $order->get_order_data();
            $customer_data = $order->get_customer_data();

            // Build body
            $body = [
                'data' => [
                    'type' => 'orders',
                    'attributes' => [
                        'order' => $order_data,
                        'customer' => $customer_data
                    ]
                ]
            ];
            
            $result = $this->post('stores/orders', $body);

            if ( isset( $result->data->attributes->redirect_url ) ) {
                return $result->data->attributes->redirect_url;
            }
            else {
                return 'No redirect URL!';
            }
        }
    }