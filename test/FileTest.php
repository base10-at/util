<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 05.02.19
 * Time: 01:42
 */

namespace AppTest\Util;


use Base10\Mixin\CanInitialise;
use Base10\Test\Mixin\HasFaker;
use Base10\Uri\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    use HasFaker;

    public function testFileCallStatic()
    {
        $this->assertInstanceOf(File::class, File::init());
    }

    public function testFileEncoding()
    {
        $File = File::init()
            ->push('üa', 'b');

        self::assertEquals('üa/b', $File->normalize());

    }


    public function testFileApi()
    {
        $File = File::init();

        self::assertEquals('', $File->normalize());
    }

    public function testGetProjectDir()
    {
        $this->assertEquals(File::getProjectDir()."", dirname(__DIR__));
    }

    public function testFileImmutable()
    {
        $words = $this->faker()->words(6);
        $File = File::init();
        $File2 = $File->push($words[0], $words[1]);

        $this->assertNotSame($File2, $File);
        self::assertEquals("$words[0]/$words[1]", $File2->normalize());
    }


}