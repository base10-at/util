<?php namespace Base10\Http;


abstract class StatusCode
{
    private function __construct()
    {
    }

    CONST OK = 200;
    CONST CREATED = 201;
    CONST ACCEPTED = 202;

    CONST BAD_REQUEST = 400;
    CONST UNAUTHORIZED = 401;
    CONST FORBIDDEN = 403;
    CONST NOT_FOUND = 404;
    CONST METHOD_NOT_ALLOWED = 404;
    CONST I_AM_A_TEAPOT = 418;

    CONST INTERNAL_SERVER_ERROR = 500;
    CONST NOT_IMPLEMENTED = 501;

}