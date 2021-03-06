<?php namespace Base10\Http;

abstract class Method
{

    /**
     * Method constructor.
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    CONST GET = 'GET';
    CONST PUT = 'PUT';
    CONST POST = 'POST';
    CONST PATCH = 'PATCH';
    CONST DELETE = 'DELETE';
    CONST OPTION = 'OPTION';
    CONST HEAD = 'HEAD';
}