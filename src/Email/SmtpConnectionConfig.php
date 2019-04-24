<?php namespace Base10\Email;


use Base10\Contract\EmailAddressInterface;
use Base10\Mixin\CanInitialise;

class SmtpConnectionConfig
{

    use CanInitialise;

    /**
     * @var string
     */
    private $_username;

    /**
     * @var string
     */
    private $_password;

    /**
     * @var string
     */
    private $_server;

    /**
     * @var integer
     */
    private $_port;

    /**
     * @var EmailAddressInterface
     */
    private $_email;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->_username;
    }

    /**
     * @param string $username
     * @return self
     */
    public function username(string $username): self
    {
        $this->_username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->_password;
    }

    /**
     * @param string $password
     * @return self
     */
    public function password(string $password): self
    {
        $this->_password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getServer(): string
    {
        return $this->_server;
    }

    /**
     * @param string $server
     * @return self
     */
    public function server(string $server): self
    {
        $this->_server = $server;
        return $this;
    }

    /**
     * @return EmailAddressInterface
     */
    public function getEmail(): EmailAddressInterface
    {
        return $this->_email;
    }

    /**
     * @param EmailAddressInterface $email
     * @return self
     */
    public function email(EmailAddressInterface $email): self
    {
        $this->_email = $email;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->_port;
    }

    /**
     * @param int $port
     * @return self
     */
    public function port(int $port): self
    {
        $this->_port = $port;
        return $this;
    }

}