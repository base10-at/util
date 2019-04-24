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


    public function testCreate()
    {

        $file = File::getProjectDir()->push("src", "somepath");
        $path = $file . '';
        $this->assertFalse(file_exists($path));
        $file->create(0640);
        $this->assertTrue(file_exists($path));
        rmdir($path);
        $this->assertFalse(file_exists($path));
    }

    public function testFileApi()
    {
        $File = File::init();

        self::assertEquals('', $File->normalize());
    }

    public function testGetProjectDir()
    {
        $this->assertEquals(File::getProjectDir() . "", dirname(__DIR__));
    }

    public function testGetPublicDir()
    {
        $this->assertEquals(
            File::getPublicDir() . "",
            dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public'
        );
    }

    public function testGetPublicDirWithPath()
    {
        $this->assertEquals(
            File::getPublicDir('path') . "",
            dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public'. DIRECTORY_SEPARATOR . 'path'
        );
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