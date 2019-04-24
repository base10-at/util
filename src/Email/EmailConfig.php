<?php namespace Base10\Email;


use Base10\Contract\EmailAddressInterface;
use Base10\Mixin\CanInitialise;

class EmailConfig
{

    use CanInitialise;

    /**
     * @var SmtpConnectionConfig
     */
    private $_connection;

    /**
     * @var EmailAddressInterface[];
     */
    private $_to = [];

    /**
     * @var EmailAddressInterface[];
     */
    private $_cc = [];

    /**
     * @var EmailAddressInterface[];
     */
    private $_bcc = [];

    /**
     * @var string;
     */
    private $_subject = "";

    /**
     * @var string;
     */
    private $_body = "";

    private $_attachments = [];

    /**
     * @return SmtpConnectionConfig
     */
    public function getConnection(): SmtpConnectionConfig
    {
        return $this->_connection;
    }

    /**
     * @param SmtpConnectionConfig $connection
     * @return EmailConfig
     */
    public function connection(SmtpConnectionConfig $connection): EmailConfig
    {
        $this->_connection = $connection;
        return $this;
    }

    /**
     * @return EmailAddressInterface[]
     */
    public function getTo(): array
    {
        return $this->_to;
    }

    /**
     * @param EmailAddressInterface[] $to
     * @return EmailConfig
     */
    public function to(array $to): EmailConfig
    {
        $this->_to = $to;
        return $this;
    }

    /**
     * @return EmailAddressInterface[]
     */
    public function getCc(): array
    {
        return $this->_cc;
    }

    /**
     * @param EmailAddressInterface[] $cc
     * @return EmailConfig
     */
    public function cc(array $cc): EmailConfig
    {
        $this->_cc = $cc;
        return $this;
    }

    /**
     * @return EmailAddressInterface[]
     */
    public function getBcc(): array
    {
        return $this->_bcc;
    }

    /**
     * @param EmailAddressInterface[] $bcc
     * @return EmailConfig
     */
    public function bcc(array $bcc): EmailConfig
    {
        $this->_bcc = $bcc;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->_subject;
    }

    /**
     * @param string $subject
     * @return EmailConfig
     */
    public function subject(string $subject): EmailConfig
    {
        $this->_subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->_body;
    }

    /**
     * @param string $body
     * @return EmailConfig
     */
    public function body(string $body): EmailConfig
    {
        $this->_body = $body;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->_attachments;
    }

    /**
     * @param array $attachments
     * @return EmailConfig
     */
    public function attachments(array $attachments): EmailConfig
    {
        $this->_attachments = $attachments;
        return $this;
    }


}