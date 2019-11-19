# Utrust PHP

[![CircleCI](https://circleci.com/gh/utrustdev/utrust-php.svg?style=svg)](https://circleci.com/gh/utrustdev/utrust-php)

The official PHP library for the [Utrust API](https://docs.api.utrust.com).

## Minimum Requirements

- PHP version 5.4 or above.

## Install

Install with composer:

```
composer require utrust/utrust
```

## Usage

### API Client

Creating a new Order:

```
use Utrust\ApiClient;
use Utrust\Store\Customer;
use Utrust\Store\Order;

$order = new Order([...]);
$customer = new Customer([...]);

$utrust_api = new ApiClient('API_KEY');
$response = $utrust_api->createOrder($order, $customer);
echo $response->attributes->redirect_url;
```

### Validations

The `Validator` class can be used to check your data array before pass it to the API Client. It will throw an Exception if the validations don't pass with the errors array, otherwise it will return `true`:

```
$orderIsValid = Validator::order($orderData);
$customerIsValid = Validator::customer($customerData);

if ($orderIsValid == true || $customerIsValid == true) {
    // create the other here
}
```

### Webhooks

The `Webhook\Event` class can be used to verify an incoming event via Webhook. It will throw an Exception if the event has an invalid format or invalid signature:

```
$event = new Event($payload);
$event->validateSignature($webhooksSecret);
```

Check the folder `examples/` for more info.

## Tests

To run the tests:

```
composer test
```
