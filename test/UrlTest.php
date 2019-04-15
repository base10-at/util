<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 05.02.19
 * Time: 01:42
 */

namespace AppTest\Util;


use Base10\Test\Mixin\HasFaker;
use Base10\Uri\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    use HasFaker;

    public function testUrlCallStatic()
    {
        $this->assertInstanceOf(Url::class, Url::init());
        $this->assertInstanceOf(Url::class, Url::api());
        $this->assertInstanceOf(Url::class, Url::random());
    }

    public function testUrlEncoding()
    {
        $url = Url::init()
            ->push('üa', 'b')
            ->putParams(['x' => 3, "üy" => 4]);

        self::assertEquals('%C3%BCa/b?x=3&%C3%BCy=4', $url->normalize());

    }

    public function testUrlPutParams()
    {

        $words = $this->faker()->words(6);

        $url = Url::init()
            ->push($words[0], $words[1])
            ->putParams([$words[2] => $words[3], $words[4] => $words[5]]);

        self::assertEquals("$words[0]/$words[1]?$words[2]=$words[3]&$words[4]=$words[5]", $url->normalize());
    }

    public function testUrlApi()
    {
        $url = Url::api();

        self::assertEquals('api', $url->normalize());
    }

    public function testUrlApiImmutable()
    {
        $words = $this->faker()->words(6);
        $url = Url::api();
        $url2 = $url->push($words[0], $words[1]);
        $url3 = $url2->putParams([$words[2] => $words[3], $words[4] => $words[5]]);

        $this->assertNotSame($url2, $url);
        $this->assertNotSame($url3, $url2);
        self::assertEquals("api/$words[0]/$words[1]?$words[2]=$words[3]&$words[4]=$words[5]", $url3->normalize());
    }


    public function testAssertImmutable()
    {
        $url = Url::api('a', 'b');
        $other = $url->putParams(['x' => 3, "y" => 4]);

        $this->assertNotSame($other, $url);
        self::assertEquals('api/a/b?x=3&y=4', $other->normalize());
    }

    public function testHttpPrefix()
    {
        $url = Url::init('http://www.base10.at');
        $url2 = $url->push('x');
        $other = $url2->putParams(['x' => 3, "y" => 4]);

        $this->assertNotSame($other, $url2);
        $this->assertNotSame($other, $url);
        $this->assertNotSame($url2, $url);
        self::assertEquals('http://www.base10.at/x?x=3&y=4', $other->normalize());
    }

    public function testHttpsPrefix()
    {
        $url = Url::init('https://www.base10.at');
        $url2 = $url->push('x');
        $other = $url2->putParams(['x' => 3, "y" => 4]);

        $this->assertNotSame($other, $url);
        self::assertEquals('https://www.base10.at/x?x=3&y=4', $other->normalize());
    }
}