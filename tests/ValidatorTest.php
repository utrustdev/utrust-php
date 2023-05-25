<?php
use PHPUnit\Framework\TestCase;
use Utrust\Validator;

class ValidatorTest extends TestCase
{
    public function testValidCustomer(): void
    {
        $validCustomerData = [
            'first_name' => 'Daniel',
            'last_name' => 'Coelho',
            'email' => 'daniel+php@utrust.com',
            'country' => 'PT',
        ];

        $result = Validator::customer($validCustomerData);
        $this->assertTrue($result);
    }

    public function testInvalidCustomer(): void
    {
        // Missing required field email
        $invalidCustomerData = [
            'first_name' => 'Daniel',
            'last_name' => 'Coelho',
            'postcode' => '4000',
            'country' => 'PT',
        ];

        $expected_errors_message = print_r([
            'email' => [
                'Email is required',
                'Email is not a valid email address',
            ],
        ], true);

        $this->expectExceptionMessage($expected_errors_message);
        Validator::customer($invalidCustomerData);
    }

    public function testValidOrder(): void
    {
        $validOrderData = [
            'reference' => 'REF-12345678',
            'amount' => [
                'total' => '0.99',
                'currency' => 'EUR',
            ],
            'return_urls' => [
                'return_url' => 'http://example.com/order_success',
                'cancel_url' => 'http://example.com/order_canceled',
                'callback_url' => 'http://example.com/webhook_url',
            ],
    
        ];

        $result = Validator::order($validOrderData);
        $this->assertTrue($result);
    }

    public function testInvalidOrder(): void
    {
        // Missing some required fields
        $invalidOrderData = [
            'amount' => [
                'total' => '0.99',
                'currency' => 'EURO',
            ],
            'return_urls' => [
                'return_url' => 'http://example.com/order_success',
                'cancel_url' => 'example.com/this-is-not-a-correct-url',
            ],
        ];

        $expected_errors_message = print_r([
            "reference" => ["Reference is required"],
            "amount.currency" => [
                "Amount.currency contains invalid characters",
            ],
            "return_urls.cancel_url" => [
                "Return Urls.cancel Url is not a valid URL",
            ],
        ], true);

        $this->expectExceptionMessage($expected_errors_message);
        Validator::order($invalidOrderData);
    }
}
