<?php

namespace MonologPHPMailer;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\MailHandler;
use Monolog\Logger;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * PHPMailer handler for Monolog 2.x.
 *
 * It uses [PHPMailer](https://github.com/PHPMailer/PHPMailer/) to send emails.
 *
 * @since 1.0.0
 *
 * @author Filip Š <projects@filips.si>
 *
 * @license MIT
 *
 * @package MonologPHPMailer
 */
class PHPMailerHandler2 extends MailHandler
{
    /**
     * A PHPMailer instance.
     *
     * @var PHPMailer $mailer
     */
    protected $mailer;

    /**
     * Constructs the PHPMailer handler.
     *
     * @param PHPMailer  $mailer A PHPMailer instance to use.
     * @param int|string $level  The minimum logging level at which this handler will be triggered.
     * @param bool       $bubble Whether the messages that are handled can bubble up the stack or not.
     */
    public function __construct(PHPMailer $mailer, $level = Logger::ERROR, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->mailer = $mailer;
    }

    /**
     * Sends a mail with the given content.
     *
     * @param string $content Formatted email body to be sent.
     * @param array  $records The array of log records that formed this content.
     *
     * @return void
     */
    protected function send(string $content, array $records): void
    {
        $mailer = $this->buildMessage($content, $records);
        $mailer->send();
    }

    /**
     * Builds a message to be sent.
     *
     * @param string    $content Formatted email body to be sent.
     * @param array     $records The array of log records that formed this content.
     *
     * @return PHPMailer Builded message.
     */
    public function buildMessage(string $content, array $records): PHPMailer
    {
        $mailer = clone $this->mailer;

        if (substr($content, 0, 1) == '<') {
            $mailer->isHTML(true);
        }

        // @codingStandardsIgnoreStart
        if ($records) {
            $subjectFormatter = new LineFormatter($mailer->Subject);
            $mailer->Subject = $subjectFormatter->format($this->getHighestRecord($records));
        }
        // @codingStandardsIgnoreEnd

        // @codingStandardsIgnoreStart
        $mailer->Body = $content;
        // @codingStandardsIgnoreEnd

        return $mailer;
    }
}
