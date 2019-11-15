<?php
    namespace Utrust\Resources;

    class Customer
    {   
        private $data;

        public function __construct($data)
        {
            // Data validations
            $this->data = $data;
        }

        /**
         * Gets Customer data
         *
         * @return array Customer data.
         */
        public function getData() {
            return $this->data;
        }
    }