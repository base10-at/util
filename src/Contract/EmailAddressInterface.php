<?php namespace Base10\Contract;


interface EmailAddressInterface
{
    /**
     * @return string
     */
    public function getAlias();

    /**
     * @return string
     */
    public function getAddress();
}