<?php
namespace Utrust;

class Customer
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Gets Customer data
     *
     * @return array Customer data.
     */
    public function getData()
    {
        return $this->data;
    }
}
