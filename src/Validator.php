<?php
namespace Utrust;

// require_once '../vendor/autoload.php';

use Valitron;

class Validator
{
    public static function customer($data)
    {
        $validator = new Valitron\Validator($data);

        $validator->rules([
            'required' => [
                ['first_name'],
                ['last_name'],
                ['email'],
                ['country'],
            ],
            'optional' => [
                ['address1'],
                ['address2'],
                ['city'],
                ['state'],
                ['postcode'],
            ],
            'email' => [
                ['email'],
            ],
            'regex' => [
                ['country', '/^[a-zA-Z]{2}$/'],
            ],
        ]);

        if ($validator->validate()) {
            return true;
        } else {
            throw new \Exception(print_r($validator->errors(), true));
        }
    }

    public static function order($data)
    {
        $validator = new Valitron\Validator($data);

        $validator->rules([
            'required' => [
                ['reference'],
                ['amount'],
                ['amount.total'],
                ['amount.currency'],
                ['return_urls'],
                ['return_urls.return_url']
            ],
            'optional' => [
                ['amount.details'],
                ['amount.details.subtotal'],
                ['amount.details.tax'],
                ['amount.details.shipping'],
                ['amount.details.discount'],
                ['line_items'],
                ['line_items.*.name'],
                ['line_items.*.price'],
                ['line_items.*.currency'],
                ['line_items.*.quantity'],
                ['line_items.*.sku'],
                ['return_urls.cancel_url'],
                ['return_urls.callback_url'],
            ],
            'array' => [
                ['amount'],
                ['return_urls'],
                ['line_items'],
            ],
            'integer' => [
                ['line_items.*.quantity'],
            ],
            'regex' => [
                ['amount.currency', '/^[a-zA-Z]{3}$/'],
                ['line_items.*.currency', '/^[a-zA-Z]{3}$/'],
            ],
            'url' => [
                ['return_urls.return_url'],
                ['return_urls.cancel_url'],
                ['return_urls.callback_url'],
            ],
        ]);

        if ($validator->validate()) {
            return true;
        } else {
            throw new \Exception(print_r($validator->errors(), true));
        }
    }
}
