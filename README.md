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

```php
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

The `Validator` class can be used to check your data array before passing it to the API Client.
It will throw an exception with the errors array if the validations don't pass, otherwise it will return `true`:

```php
$orderIsValid = Validator::order($orderData);
$customerIsValid = Validator::customer($customerData);

if ($orderIsValid || $customerIsValid) {
    // create the order here
}
```

### Webhooks

The `Webhook\Event` class can be used to verify an incoming event via Webhook.
It will throw an exception if the event has an invalid format or invalid signature:

```php
$event = new Event($payload);
$event->validateSignature($webhooksSecret);
```

Check the `examples/` directory for further details.

## Contribute

This library was written and is maintained by the Utrust development team.
We have now opened it to the world so that the community using this library may have the chance of shaping its development.

You can contribute by simply letting us know your suggestions or any problems that you find [by opening an issue on GitHub](https://github.com/utrustdev/utrust-php/issues/new).

You can also fork the repository on GitHub and open a pull request for the `master` branch with your missing features and/or bug fixes.
Please make sure the new code follows the same style and conventions as already written code.
Our team is eager to welcome new contributors into the mix :blush:.

### Tests

When contributing with new changes, please make an effort to provide the respective tests.
This is especially important when fixing any problems, as it will prevent other contributors
from accidentally reintroducing the issue in the future.

Before submitting a pull request with your changes, please make sure every test passes:

```
composer test
```

When in doubt whether you caused a test to fail, check the build for `master` in
[CircleCI](https://circleci.com/gh/utrustdev/utrust-php).

### Lint

This project uses [PHPCodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) with PSR-2 coding stardard.

Before contributing your changes, make sure it passes on the linter:

```
composer lint
```

When in doubt whether you introduced a linter error, check the build for `master` in
[CircleCI](https://circleci.com/gh/utrustdev/utrust-php).

## License

Utrust PHP is maintained with :purple_heart: by the Utrust development team,
and is available to the public under the GNU GPLv3 license.
Please see [LICENSE](https://github.com/utrustdev/utrust-php/blob/master/LICENSE) for further details.

&copy; Utrust 2019
