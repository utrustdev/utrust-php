<?php
namespace Utrust;

class Order
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Gets Order data
     *
     * @return array Order data.
     */
    public function getData()
    {
        return $this->data;
    }
}
