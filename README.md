# Invoice-Bundle

[![Latest Stable Version](https://poser.pugx.org/flexphp/invoice-bundle/v/stable)](https://packagist.org/packages/flexphp/invoice-bundle)
[![Total Downloads](https://poser.pugx.org/flexphp/invoice-bundle/downloads)](https://packagist.org/packages/flexphp/invoice-bundle)
[![Latest Unstable Version](https://poser.pugx.org/flexphp/invoice-bundle/v/unstable)](https://packagist.org/packages/flexphp/invoice-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/flexphp/flex-invoice-bundle/badges/quality-score.png)](https://scrutinizer-ci.com/g/flexphp/flex-invoice-bundle)
[![Code Coverage](https://scrutinizer-ci.com/g/flexphp/flex-invoice-bundle/badges/coverage.png)](https://scrutinizer-ci.com/g/flexphp/flex-invoice-bundle)
[![License](https://poser.pugx.org/flexphp/invoice-bundle/license)](https://packagist.org/packages/flexphp/invoice-bundle)
[![composer.lock](https://poser.pugx.org/flexphp/invoice-bundle/composerlock)](https://packagist.org/packages/flexphp/invoice-bundle)

Invoice bundle for Symfony

Change between frameworks when you need. Keep It Simple, SOLID and DRY with FlexPHP.

## Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
composer require invoice-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require flexphp/invoice-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    FlexPHP\Bundle\FlexPHPInvoiceBundle::class => ['all' => true],
];
```

## License

Invoice-bundle is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
