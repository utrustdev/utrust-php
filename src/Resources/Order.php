<?php
    namespace Utrust\Resources;

    class Order
    {   
        private $data;

        public function __construct($data)
        {
            // Data validations
            $this->data = $data;
        }

        /**
         * Gets Order data
         *
         * @return array Order data.
         */
        public function getData() {
            return $this->data;
        }
    }