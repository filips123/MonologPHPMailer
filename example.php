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
