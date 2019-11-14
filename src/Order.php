<?php
    namespace Utrust;

    class Order
    {   
        private $order_data;
        private $customer_data;

        public function __construct($order_data, $customer_data)
        {
            // Data validations
            $this->order_data = $order_data;
            $this->customer_data = $customer_data;
        }

        /**
         * Gets Order data
         *
         * @return array Order data.
         */
        public function get_order_data() {
            return $this->order_data;
        }

        /**
         * Gets Order data
         *
         * @return array Order data.
         */
        public function get_customer_data() {
            return $this->customer_data;
        }
    }