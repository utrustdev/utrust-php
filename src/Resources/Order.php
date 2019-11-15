<?php
    namespace Utrust\Resources;

    class Order
    {   
        private $data;
        private $required_data = [
            'reference' => '',
            'amount' => [
                'total' => '',
                'currency' => '',
            ],
            'return_urls' => [
                'return_url'  => '',
            ],
            'line_items' => [
                [
                    'sku' => '',
                    'name' => '',
                    'price' => '',
                    'currency' => '',
                    'quantity' => ''
                ]
            ]
        ];

        public function __construct($data)
        {
            // Data validations
            $missing_data = $this->checkRequired( $data );
            if ( $missing_data == [] ) {
                $this->data = $data;
            }
            else {
                throw new \Exception( "Exception: Missing keys! " . print_r ($missing_data, true) );
            } 
        }

        /**
         * Gets Order data
         *
         * @return array Order data.
         */
        public function getData() {
            return $this->data;
        }

        /**
         * Check required keys.
         * @param array $data The input data.
         *
         * @return array Missing keys.
         */
        private function checkRequired( $data ){
            $missing = [];

            foreach ( $this->required_data as $key=>$value ) {
                if ( empty( $data[$key] ) ) {
                    $missing[] = $key;
                } else if ( is_array($value) ) {
                    $missing = array_merge( $this->checkRequired( $data[$key], $value ), $missing );
                }
            }

            return $missing;
        }
    }