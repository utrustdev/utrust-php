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

The project is using PHPUnit for testing classes.
To run the tests:

```
composer test
```

## Contribute

This library was wrriten and it's maintained by the Utrust development team.
We have now opened it to the world so that the community using this plugin may have the chance of shaping its development.
You can contribute by simply letting us know your suggestions or any problems that you find [by opening an issue on GitHub[https://github.com/utrustdev/utrust-php/issues/new].
You can also fork the repository on GitHub and open a pull request for the `master` branch with your missing features and/or bug fixes.
Please make sure the new code follows the same style and conventions as already written code.
Our team is eager to welcome new contributors into the mix :blush:.

## License

The Utrust PHP maintained with :purple_heart: by the Utrust development team, and is available to the public under the GNU GPLv3 license. Please see [LICENSE](https://github.com/utrustdev/utrust-php/blob/master/LICENSE) for further details.

&copy; Utrust 2019
