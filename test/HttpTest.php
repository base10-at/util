<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 05.02.19
 * Time: 01:42
 */

namespace AppTest\Util;


use Base10\Http\Method;
use Base10\Http\StatusCode;
use PHPUnit\Framework\TestCase;

class HttpTest extends TestCase
{

    public function testMethod()
    {
        $constants = ['GET', 'PUT', 'POST', 'PATCH', 'DELETE', 'OPTION', 'HEAD'];

        $this->assertEquals(
            array_combine($constants, $constants),
            (new \ReflectionClass(Method::class))
                ->getConstants());
    }

    public function testStatus()
    {
        $constants =
            [
                "OK" => 200,
                "CREATED" => 201,
                "ACCEPTED" => 202,

                "BAD_REQUEST" => 400,
                "UNAUTHORIZED" => 401,
                "FORBIDDEN" => 403,
                "NOT_FOUND" => 404,
                "METHOD_NOT_ALLOWED" => 404,
                "I_AM_A_TEAPOT" => 418,

                "INTERNAL_SERVER_ERROR" => 500,
                "NOT_IMPLEMENTED" => 501,
            ];

        $this->assertEquals(
            $constants,
            (new \ReflectionClass(StatusCode::class))
                ->getConstants());
    }


}