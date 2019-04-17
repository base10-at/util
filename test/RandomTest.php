<?php namespace AppTest\Service\Base;

use Base10\Util\Random;
use PHPUnit\Framework\TestCase;

class RandomTest extends TestCase
{

    /**
     * @var Random
     */
    private $random;
    const LENGTH = 128;


    protected function setUp(): void
    {
        parent::setUp();
        $this->random = new Random();
    }


    public function testCharSetSingle()
    {
        $result = $this->random->charset(self::LENGTH, ['a']);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertEquals(str_repeat('a', self::LENGTH), $result);
    }

    public function testCharSetEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('charset is empty');
        $this->random->charset(self::LENGTH, []);
    }

    public function testCharSetEmptyString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('charset is empty');
        $this->random->charset(self::LENGTH, ['']);
    }

    public function testCharSetMultiple()
    {
        $result = $this->random->charset(self::LENGTH, ['a', 'b']);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertInSet($result, ['a', 'b']);
    }

    public function testHex()
    {
        $result = $this->random->generate(self::LENGTH, Random::HEX);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertIsNumeric(hexdec($result));
        $this->assertInSet($result, $this->hex());
        $this->assertNotInSet($result, $this->upperCase());
        $this->assertNotInSet($result, range("g", 'z'));
    }

    public function testHexORNumeric()
    {
        $result = $this->random->generate(self::LENGTH, Random::HEX | Random::NUMBER);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertIsNumeric(hexdec($result));
        $this->assertInSet($result, $this->hex());
        $this->assertNotInSet($result, $this->upperCase());
        $this->assertNotInSet($result, range("g", 'z'));
    }

    public function testNumeric()
    {
        $result = $this->random->generate(self::LENGTH, Random::NUMBER);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertIsNumeric(hexdec($result));
        $this->assertIsNumeric($result);
        $this->assertInSet($result, $this->digit());
        $this->assertNotInSet($result, $this->lowerCase());
        $this->assertNotInSet($result, $this->upperCase());
    }

    public function testLower()
    {
        $result = $this->random->generate(self::LENGTH, Random::LOWER_CASE);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertInSet($result, $this->lowerCase());
        $this->assertNotInSet($result, $this->upperCase());
        $this->assertNotInSet($result, $this->digit());
    }

    public function testUpper()
    {
        $result = $this->random->generate(self::LENGTH, Random::UPPER_CASE);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertInSet($result, $this->upperCase());
        $this->assertNotInSet($result, $this->lowerCase());
        $this->assertNotInSet($result, $this->digit());
    }

    public function testUpperOrLower()
    {
        $result = $this->random->generate(self::LENGTH, Random::UPPER_CASE | Random::LOWER_CASE);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertInSet($result, $this->upperORLowerCase());
        $this->assertNotInSet($result, $this->digit());
    }

    private function assertInSet(string $value, array $set)
    {
        foreach (str_split($value) as $char) {
            $this->assertContains($char, $set);
        }

    }

    private function assertNotInSet(string $value, array $set)
    {
        foreach (str_split($value) as $char) {
            $this->assertNotContains($char, $set);
        }
    }

    /**
     * @return array
     */
    private function lowerCase()
    {
        return range('a', 'z');
    }

    /**
     * @return array
     */
    private function upperCase()
    {
        return range('A', 'Z');
    }

    /**
     * @return array
     */
    private function digit()
    {
        return str_split(implode('', range('0', '9')));
    }

    /**
     * @return array
     */
    private function hex()
    {
        return array_merge(range('a', 'f'), $this->digit());
    }

    /**
     * @return array
     */
    private function upperORLowerCase()
    {
        return array_merge($this->upperCase(), $this->lowerCase());
    }
}