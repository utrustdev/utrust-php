# Utrust PHP

The official PHP library for the [Utrust API](https://docs.api.utrust.com).

## Minimum Requirements

- PHP version 5.4 or above.

## Install

Install with composer:

```
composer require utrust/utrust
```

## Usage

### Order

Creating a new Order:

```
use Utrust\ApiClient;
use Utrust\Store\Customer;
use Utrust\Store\Order;

$order = new Order([...]);
$costumer = new Customer([...]);

$utrust_api = new ApiClient('API_KEY');
$response = $utrust_api->createOrder($order, $costumer);
echo $response->attributes->redirect_url;
```

### Webhooks

Verifying an event (incoming via Webhook):

```
$event = new Event($payload);
$event->validateSignature($webhooksSecret);
```

Check the folder `examples` for more info.

## Tests

To run the tests:

```
composer test
```
