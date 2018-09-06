<?php

namespace MonologPHPMailer\Tests;

use DateTime;
use Monolog\Formatter\FormatterInterface;
use Monolog\Logger;

/**
 * Lets you easily generate log records and a dummy formatter for testing purposes.
 *
 * Originally part of Monolog itself. Included tor backward compatibility with Monolog 1.x.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array Record
     */
    protected function getRecord($level = Logger::WARNING, $message = 'test', $context = [])
    {
        return [
            'message' => (string) $message,
            'context' => $context,
            'level' => $level,
            'level_name' => Logger::getLevelName($level),
            'channel' => 'test',
            'datetime' => DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true))),
            'extra' => [],
        ];
    }

    protected function getMultipleRecords()
    {
        return [
            $this->getRecord(Logger::DEBUG, 'debug message 1'),
            $this->getRecord(Logger::DEBUG, 'debug message 2'),
            $this->getRecord(Logger::INFO, 'information'),
            $this->getRecord(Logger::WARNING, 'warning'),
            $this->getRecord(Logger::ERROR, 'error'),
        ];
    }

    /**
     * @suppress PhanTypeMismatchReturn
     */
    protected function getIdentityFormatter()
    {
        $formatter = $this->createMock('\Monolog\Formatter\FormatterInterface');
        $formatter->expects($this->any())
            ->method('format')
            ->will(
                $this->returnCallback(
                    function ($record) {
                        return $record['message'];
                    }
                )
            );

        return $formatter;
    }
}
