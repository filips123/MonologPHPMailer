<?php

namespace MonologPHPMailer\Tests;

use MonologPHPMailer\PHPMailerHandler;
use Monolog\Logger;
use PHPMailer\PHPMailer\PHPMailer;

class PHPMailerHandlerTest extends TestCase
{
    /**
     * @var \PHPMailer\PHPMailer\PHPMailer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $mailer;

    public function setUp()
    {
        $this->mailer = $this
            ->getMockBuilder('\PHPMailer\PHPMailer\PHPMailer')
            ->setConstructorArgs([true])
            ->getMock();

        $this->mailer->Subject = 'Error: %level_name% %message%';
    }

    /**
     * @covers \MonologPHPMailer\PHPMailerHandler::__construct()
     * @covers \MonologPHPMailer\PHPMailerHandler::send()
     * @covers \MonologPHPMailer\PHPMailerHandler::buildMessage()
     */
    public function testMessageSended()
    {
        $this->mailer
            ->expects($this->once())
            ->method('send');

        $handler = new PHPMailerHandler($this->mailer);

        $handler->handleBatch([$this->getRecord(Logger::ERROR, 'error')]);
    }

    /**
     * @covers \MonologPHPMailer\PHPMailerHandler::__construct()
     * @covers \MonologPHPMailer\PHPMailerHandler::send()
     * @covers \MonologPHPMailer\PHPMailerHandler::buildMessage()
     */
    public function testMessageNotSendedForLowLevel()
    {
        $this->mailer
            ->expects($this->never())
            ->method('send');

        $handler = new PHPMailerHandler($this->mailer);

        $handler->handleBatch([$this->getRecord(Logger::DEBUG, 'debug')]);
    }

    /**
     * @covers \MonologPHPMailer\PHPMailerHandler::__construct()
     * @covers \MonologPHPMailer\PHPMailerHandler::buildMessage()
     */
    public function testMessageSubjectAndBodyFormatting()
    {
        $handler = new PHPMailerHandler($this->mailer);

        $mailer = $handler->buildMessage('<h1>test</h1>', [$this->getRecord(Logger::ALERT, 'test')]);

        // @codingStandardsIgnoreStart
        $this->assertEquals('Error: ALERT test', $mailer->Subject);
        $this->assertEquals('<h1>test</h1>', $mailer->Body);
        // @codingStandardsIgnoreEnd
    }
}
