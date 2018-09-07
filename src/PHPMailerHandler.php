<?php

/**
 * Loads required handler.
 *
 * It loads required handler class for Monolog 1.x or 2.x depending on its version.
 *
 * @since 1.0.0
 *
 * @author Filip Š <projects@filips.si>
 *
 * @license MIT
 *
 * @package MonologPHPMailer
 */

use Monolog\Logger;

if (Logger::API == 1) {
    class_alias('MonologPHPMailer\PHPMailerHandler1', 'MonologPHPMailer\PHPMailerHandler');
} else {
    class_alias('MonologPHPMailer\PHPMailerHandler2', 'MonologPHPMailer\PHPMailerHandler');
}
