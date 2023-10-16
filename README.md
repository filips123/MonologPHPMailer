

MonologPHPMailer
================

[![version][icon-version]][link-packagist]
[![downloads][icon-downloads]][link-packagist]
[![license][icon-license]][link-license]
[![php][icon-php]][link-php]
[![build][icon-build]][link-build]
[![coverage][icon-coverage]][link-coverage]
[![quality][icon-quality]][link-quality]

MonologPHPMailer is a [PHPMailer][link-phpmailer] handler for [Monolog][link-monolog]. It enables you to send logs to emails with PHPMailer.

## Installation

### Requirements

MonologPHPMailer requires [PHP][link-php] 8.1 or higher, [Monolog][link-monolog] 3.x, and [PHPMailer][link-phpmailer] 6.x.

### Using Composer

The reccomended way to install MonologPHPMailer is with [Composer][link-composer], dependency manager for PHP.

You should just add `filips123/monolog-phpmailer` to your project dependencies in `composer.json`. It will also install Monolog and PHPMailer, but it is recommended to add them manually to `composer.json`.

```json
{
    "require": {
        "monolog/monolog": "^3.0",
        "phpmailer/phpmailer": "^6.0",
        "filips123/monolog-phpmailer": "^2.0"
    }
}
```

Do not forget to run `composer install` and add `require 'vendor/autoload.php';` to your main script.

### Manually Installation

Alternatively, you could download all files in directory [`src`][link-handlers] from GitHub and then manually include them in your script. You also have to install Monolog and PHPMailer manually.

## Usage

You should just add handler `MonologPHPMailer\PHPMailerHandler` to your logger. It's first argument must be PHPMailer instance.

## Example

```php
<?php

use MonologPHPMailer\PHPMailerHandler;

use Monolog\Formatter\HtmlFormatter;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;

use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/vendor/autoload.php';

$mailer = new PHPMailer(true);
$logger = new Logger('logger');

$mailer->isSMTP();
$mailer->Host = 'smtp.example.com';
$mailer->SMTPAuth = true;
$mailer->Username = 'server@example.com';
$mailer->Password = 'password';

$mailer->setFrom('server@example.com', 'Logging Server');
$mailer->addAddress('user@example.com', 'Your Name');

$logger->pushProcessor(new IntrospectionProcessor);
$logger->pushProcessor(new MemoryUsageProcessor);
$logger->pushProcessor(new WebProcessor);

$handler = new PHPMailerHandler($mailer);
$handler->setFormatter(new HtmlFormatter);

$logger->pushHandler($handler);

$logger->error('Error!');
$logger->alert('Something went wrong!');

```

## Versioning
This library uses [SemVer][link-semver] SemVer for versioning. For the versions available, see the [tags on this repository][link-tags].

## License
This library is licensed under the MIT license. See the [LICENSE][link-license-file] file for details.

[icon-version]: https://img.shields.io/packagist/v/filips123/monolog-phpmailer.svg?style=flat-square&label=version
[icon-downloads]: https://img.shields.io/packagist/dt/filips123/monolog-phpmailer.svg?style=flat-square&label=downloads
[icon-license]: https://img.shields.io/packagist/l/filips123/monolog-phpmailer.svg?style=flat-square&label=license
[icon-php]: https://img.shields.io/packagist/php-v/filips123/monolog-phpmailer.svg?style=flat-square&label=php
[icon-build]: https://img.shields.io/github/actions/workflow/status/filips123/MonologPHPMailer/main.yml?style=flat-square&label=build
[icon-coverage]: https://img.shields.io/scrutinizer/coverage/g/filips123/MonologPHPMailer.svg?style=flat-square&label=coverage
[icon-quality]: https://img.shields.io/scrutinizer/g/filips123/MonologPHPMailer.svg?style=flat-square&label=quality

[link-packagist]: https://packagist.org/packages/filips123/monolog-phpmailer/
[link-license]: https://choosealicense.com/licenses/mit/
[link-php]: https://php.net/
[link-build]: https://github.com/filips123/MonologPHPMailer/actions
[link-coverage]: https://scrutinizer-ci.com/g/filips123/MonologPHPMailer/code-structure/
[link-quality]: https://scrutinizer-ci.com/g/filips123/MonologPHPMailer/

[link-monolog]: https://github.com/Seldaek/monolog/
[link-phpmailer]: https://github.com/PHPMailer/PHPMailer/
[link-composer]: https://getcomposer.org/
[link-handlers]: https://github.com/filips123/MonologPHPMailer/tree/master/src
[link-semver]: https://semver.org/
[link-tags]: https://github.com/filips123/MonologPHPMailer/tags/
[link-license-file]: https://github.com/filips123/MonologPHPMailer/blob/master/LICENSE
