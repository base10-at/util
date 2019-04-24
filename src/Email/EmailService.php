<?php namespace Base10\Email;

use Base10\Contract\EmailAddressInterface;
use Base10\Contract\EmailServiceInterface;

class EmailService implements EmailServiceInterface
{

    /**
     * @inheritDoc
     */
    public function sendEmail(EmailConfig $parameter)
    {
        return $this->send($parameter);
    }

    /**
     * @inheritDoc
     */
    public function send(EmailConfig $parameter): int
    {

        $connection = $parameter->getConnection();
        // Create the Transport
        $transport = $this->getTransport($connection);

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        // Create a message
        $message = (new \Swift_Message($parameter->getSubject()))
            ->setFrom(['test.sender@base10.at' => 'test.sender'])
            ->setTo($this->buildEmailArray($parameter->getTo()))
            ->setCc($this->buildEmailArray($parameter->getCc()))
            ->setBcc($this->buildEmailArray($parameter->getBcc()))
            ->setBody($parameter->getBody());
        foreach ($parameter->getAttachments() as $getattachment) {
            $message->attach(\Swift_Attachment::fromPath($getattachment));
        }
        // Send the message
        return $result = $mailer->send($message);
    }

    /**
     * @param EmailAddressInterface[] $emailAddresses
     * @return string []
     */
    private function buildEmailArray(array $emailAddresses)
    {
        $result = [];
        foreach ($emailAddresses as $emailAddress) {
            $alias = $emailAddress->getAlias();
            if ($alias) {
                $result[$emailAddress->getAddress()] = $alias;
            } else {
                $result[] = $emailAddress->getAddress();
            }
        }

        return $result;
    }


    /**
     * @param SmtpConnectionConfig $connection
     * @return \Swift_SmtpTransport
     */
    protected function getTransport(SmtpConnectionConfig $connection): \Swift_SmtpTransport
    {
        $transport = (new \Swift_SmtpTransport($connection->getServer(), $connection->getPort()))
            ->setUsername($connection->getUsername())
            ->setPassword($connection->getPassword());
        return $transport;
    }

}