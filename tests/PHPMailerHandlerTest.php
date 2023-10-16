<?php declare(strict_types=1);

namespace MonologPHPMailer\Tests;

use MonologPHPMailer\PHPMailerHandler;

use Monolog\Level;
use Monolog\Test\TestCase;
use PHPMailer\PHPMailer\PHPMailer;

class PHPMailerHandlerTest extends TestCase
{
    /**
     * @var \PHPMailer\PHPMailer\PHPMailer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $mailer;

    protected static function getMethod(string $class, string $name)
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        return $method;
    }

    public function setUp(): void
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
    public function testMessageSent(): void
    {
        $this->mailer
            ->expects($this->once())
            ->method('send');

        $handler = new PHPMailerHandler($this->mailer);
        $handler->handleBatch([$this->getRecord(Level::Error, 'error')]);
    }

    /**
     * @covers \MonologPHPMailer\PHPMailerHandler::__construct()
     * @covers \MonologPHPMailer\PHPMailerHandler::send()
     * @covers \MonologPHPMailer\PHPMailerHandler::buildMessage()
     */
    public function testMessageNotSentForLowLevel()
    {
        $this->mailer
            ->expects($this->never())
            ->method('send');

        $handler = new PHPMailerHandler($this->mailer);
        $handler->handleBatch([$this->getRecord(Level::Debug, 'debug')]);
    }

    /**
     * @covers \MonologPHPMailer\PHPMailerHandler::__construct()
     * @covers \MonologPHPMailer\PHPMailerHandler::buildMessage()
     */
    public function testMessageSubjectAndBodyFormatting()
    {
        $handler = new PHPMailerHandler($this->mailer);
        $builder = self::getMethod('\MonologPHPMailer\PHPMailerHandler', 'buildMessage');
        $mailer = $builder->invokeArgs($handler, ['<h1>test</h1>', [$this->getRecord(Level::Alert, 'test')]]);

        // phpcs:disable Squiz.NamingConventions.ValidVariableName
        $this->assertEquals(PHPMailer::CONTENT_TYPE_TEXT_HTML, $mailer->ContentType);
        $this->assertEquals('Error: ALERT test', $mailer->Subject);
        $this->assertEquals('<h1>test</h1>', $mailer->Body);
        // phpcs:enable
    }
}
