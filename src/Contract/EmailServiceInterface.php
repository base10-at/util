<?php namespace Base10\Contract;


use Base10\Email\EmailConfig;

interface EmailServiceInterface
{
    /**
     * @deprecated use EmailServiceInterface::send() instead
     * @param EmailConfig $parameter
     * @return int
     * @see \Swift_SmtpTransport::send()
     */
    public function sendEmail(EmailConfig $parameter);

    /**
     * @param EmailConfig $parameter
     * @return int
     * @see \Swift_SmtpTransport::send()
     */
    public function send(EmailConfig $parameter);
}