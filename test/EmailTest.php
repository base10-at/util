<?php namespace AppTest\Service\Email;

use Base10\Email\SmtpConnectionConfig;
use Base10\Contract\EmailAddressInterface;
use Base10\Contract\EmailServiceInterface;
use Base10\Email\EmailConfig;
use Base10\Email\EmailService;
use Base10\Test\Mixin\HasFaker;
use Ddeboer\Imap\ConnectionInterface;
use Ddeboer\Imap\MailboxInterface;
use PHPUnit\Framework\TestCase;
use Ddeboer\Imap;

class EmailTest extends TestCase
{
    use HasFaker;


    public function testEmailConfig()
    {

        $email1 = $this->faker()->email;
        $email1alias = $this->faker()->name;
        $email2 = $this->faker()->email;
        $email2alias = $this->faker()->name;
        $email3 = $this->faker()->email;
        $email3alias = $this->faker()->name;

        $subject = $this->faker()->sentence(rand(2, 5));
        $attachmentPath = __DIR__ . '/test_attachment.txt';
        $body = $this->faker()->text();

        $connection = self::senderConfig();
        $emailConfig = EmailConfig::init()
            ->connection($connection)
            ->subject($subject)
            ->body($body)
            ->attachments([$attachmentPath]);

        $emailConfig->to([
            self::buildEmail($email1, $email1alias)
        ]);
        $emailConfig->cc([
            self::buildEmail($email2, $email2alias)

        ]);
        $emailConfig->bcc([
            self::buildEmail($email3, $email3alias)

        ]);


        $this->assertSame($connection, $emailConfig->getConnection());
        $this->assertEquals($subject, $emailConfig->getSubject());

        $attachments = $emailConfig->getAttachments();
        $this->assertCount(1, $attachments);
        $this->assertEquals($attachmentPath, $attachments[0]);

        $TOs = $emailConfig->getTo();
        $this->assertCount(1, $TOs);
        $this->assertEquals($email1, $TOs[0]->getAddress());
        $this->assertEquals($email1alias, $TOs[0]->getAlias());

        $CCs = $emailConfig->getCc();
        $this->assertCount(1, $CCs);
        $this->assertEquals($email2, $CCs[0]->getAddress());
        $this->assertEquals($email2alias, $CCs[0]->getAlias());

        $BCCs = $emailConfig->getBcc();
        $this->assertCount(1, $BCCs);
        $this->assertEquals($email3, $BCCs[0]->getAddress());
        $this->assertEquals($email3alias, $BCCs[0]->getAlias());

        $this->assertEquals($body, $emailConfig->getBody());

    }

    public function testService()
    {

        $receiverConfig = self::receiverConfig();
        //  $this->prepareReceiver($receiverConfig);

        //        $this->markTestIncomplete("not testing anything");
        // build Config
        $subject = $this->faker()->sentence(rand(2, 5));
        $attachmentPath = __DIR__ . '/test_attachment.txt';

        $emailConfig = $this->buildEmailConfig($subject, $attachmentPath);
        $emailConfig->to([
            $receiverConfig->getEmail(),
            self::buildEmail($receiverConfig->getEmail()->getAddress(), '')
        ]);
        $emailConfig->cc([

        ]);
        $emailConfig->bcc([

        ]);

        // send email
        /** @var EmailServiceInterface $svc */
        $svc = $this->getEmailService();
        $svc->sendEmail($emailConfig);
        sleep(0);
        // get receiver
        $valid = false;
        /** @var ConnectionInterface $connection */
        $connection = null;
        while (!$valid) {
            sleep(0.1);
            if ($connection) {
                $connection->close();
            }
            $connection = $this->imapConnection($receiverConfig);
            $inbox = $connection->getMailbox('INBOX');
//            $trash = $connection->getMailbox('Trash');
            $messages = $inbox->getMessages();
            $valid = $messages->valid();
        }

        foreach ($messages as $message) {
            $this->assertEquals($subject, $message->getSubject());
            $this->assertEquals(file_get_contents($attachmentPath), $message->getAttachments()[0]->getDecodedContent());
        }
        // cleanup
        self::deleteMailbox($inbox, $connection);
//        $this->deleteMailbox($trash, $connection);

        $connection->close();

    }

    /**
     * @param $subject
     * @param $attachmentPath
     * @return EmailConfig
     */
    protected function buildEmailConfig($subject, $attachmentPath): EmailConfig
    {

        $emailParam = EmailConfig::init()
            ->connection(self::senderConfig())
            ->subject($subject)
            ->body($this->faker()->text())
            ->attachments([$attachmentPath]);

        return $emailParam;

    }

    /**
     * @param $receiverConfig SmtpConnectionConfig
     * @return ConnectionInterface
     */
    protected static function imapConnection(SmtpConnectionConfig $receiverConfig): ConnectionInterface
    {

        $connection = (new Imap\Server($receiverConfig->getServer()))
            ->authenticate(
                $receiverConfig->getUsername(),
                $receiverConfig->getPassword()
            );
        return $connection;
    }

    /**
     * @param $mailbox MailboxInterface
     * @param $connection ConnectionInterface
     */
    protected static function deleteMailbox(MailboxInterface $mailbox, ConnectionInterface $connection)
    {
        foreach ($mailbox->getMessages() as $message) {
            $message->delete();
        }
        $connection->expunge();
    }

    /**
     * @return SmtpConnectionConfig
     */
    protected static function senderConfig(): SmtpConnectionConfig
    {
        $alias = 'test.sender';
        $username = self::env("SENDER_EMAIL_USERNAME");
        $password = self::env("SENDER_EMAIL_PASSWORD");
        $address = self::env("SENDER_EMAIL_ADDRESS");
        $server = self::env("SENDER_EMAIL_SERVER");
        $port = self::env("SENDER_EMAIL_PORT");
        return SmtpConnectionConfig::init()
            ->server($server)
            ->port(+$port)
            ->email(self::buildEmail($address, $alias))
            ->username($username)
            ->password($password);

    }

    /**
     * @return SmtpConnectionConfig
     */
    protected static function receiverConfig(): SmtpConnectionConfig
    {

        $alias = 'test.receiver';
        $username = self::env("RECEIVER_EMAIL_USERNAME");
        $password = self::env("RECEIVER_EMAIL_PASSWORD");
        $address = self::env("RECEIVER_EMAIL_ADDRESS");
        $server = self::env("RECEIVER_EMAIL_SERVER");
        $port = self::env("RECEIVER_EMAIL_PORT");

        return SmtpConnectionConfig::init()
            ->server($server)
            ->port($port)
            ->email(self::buildEmail($address, $alias))
            ->username($username)
            ->password($password);
    }


    /**
     * @param string $address
     * @param string $alias
     * @return EmailAddressInterface
     */
    protected static function buildEmail(string $address, string $alias = '')
    {
        return new class($address, $alias) implements EmailAddressInterface
        {


            /**
             * @var string
             */
            private $address;
            /**
             * @var string
             */
            private $alias = "";

            /**
             *  constructor.
             * @param $address
             * @param string $alias
             */
            public function __construct(string $address, string $alias)
            {
                $this->address = $address;
                $this->alias = $alias;
            }

            /**
             * @return mixed
             */
            public function getAddress()
            {
                return $this->address;
            }

            /**
             * @return string
             */
            public function getAlias(): string
            {
                return $this->alias;
            }


        };
    }

    protected static function env($name)
    {
        if (isset($_ENV[$name])) {
            return $_ENV[$name];
        }
        //   throw new EnvironmentException($name. ' not set in $_ENV');
        self::markTestSkipped('TEST SKIPPED: variable ' . $name . ' not set in $_ENV');
    }

    /**
     * @return EmailServiceInterface
     */
    protected function getEmailService(): EmailServiceInterface
    {
        return new EmailService();
    }


    public static function setUpBeforeClass(): void
    {
        $TOKEN = '##IRuWDUHbudio7ZFeXDa47CvmzmW0nfJprYXwdAwHffOUog19LlnnvH83yO2yxC8OFQlMhRKeNx0PD';
        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
        if (!isset($_ENV[$TOKEN])) {
            self::prepareReceiver(self::receiverConfig());
            $_ENV[$TOKEN] = 1;
        }
    }

    /**
     * @param SmtpConnectionConfig $receiverConfig
     */
    protected static function prepareReceiver(SmtpConnectionConfig $receiverConfig)
    {
// get receiver
        $connection = self::imapConnection($receiverConfig);
        $inbox = $connection->getMailbox('INBOX');
        self::deleteMailbox($inbox, $connection);
        $connection->close();
    }

}