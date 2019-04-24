<?php namespace AppTest\Service\Base;

use Base10\Util\CharSet;
use Base10\Util\Random;
use InvalidArgumentException;
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
        $this->assertNotInSet($result, CharSet::digit());
        $this->assertNotInSet($result, CharSet::upperCase());
        $this->assertNotInSet($result, range("b", 'z'));
        $this->assertNotInSet($result, CharSet::special());
        $this->assertNotInSet($result, CharSet::brackets());
    }

    public function testCharSetEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('charset is empty');
        $this->random->charset(self::LENGTH, []);
    }

    public function testCharSetEmptyString()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('charset is empty');
        $this->random->charset(self::LENGTH, ['']);
    }

    public function testCharSetMultiple()
    {
        $result = $this->random->charset(self::LENGTH, ['a', 'b']);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertInSet($result, ['a', 'b']);
        $this->assertNotInSet($result, CharSet::digit());
        $this->assertNotInSet($result, CharSet::upperCase());
        $this->assertNotInSet($result, range("c", 'z'));
        $this->assertNotInSet($result, CharSet::special());
        $this->assertNotInSet($result, CharSet::brackets());
    }

    public function testHex()
    {
        $result = $this->random->generate(self::LENGTH, CharSet::HEX);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertIsNumeric(hexdec($result));
        $this->assertInSet($result, CharSet::hex());
        $this->assertNotInSet($result, CharSet::upperCase());
        $this->assertNotInSet($result, range("g", 'z'));
        $this->assertNotInSet($result, CharSet::special());
        $this->assertNotInSet($result, CharSet::brackets());
    }

    public function testInvalidFlag()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->random->generate(self::LENGTH, 0);
    }
    public function testInvalidFlag2()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->random->generate(self::LENGTH, CharSet::ALL+1);
    }

    public function testHexORNumeric()
    {
        $result = $this->random->generate(self::LENGTH, CharSet::HEX | CharSet::NUMBER);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertIsNumeric(hexdec($result));
        $this->assertInSet($result, CharSet::hex());
        $this->assertNotInSet($result, CharSet::upperCase());
        $this->assertNotInSet($result, range("g", 'z'));
        $this->assertNotInSet($result, CharSet::special());
        $this->assertNotInSet($result, CharSet::brackets());
    }

    public function testNumeric()
    {
        $result = $this->random->generate(self::LENGTH, CharSet::NUMBER);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertIsNumeric(hexdec($result));
        $this->assertIsNumeric($result);
        $this->assertInSet($result, CharSet::digit());
        $this->assertNotInSet($result, CharSet::lowerCase());
        $this->assertNotInSet($result, CharSet::upperCase());
        $this->assertNotInSet($result, CharSet::special());
        $this->assertNotInSet($result, CharSet::brackets());
    }

    public function testLower()
    {
        $result = $this->random->generate(self::LENGTH, CharSet::LOWER_CASE);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertInSet($result, CharSet::lowerCase());
        $this->assertNotInSet($result, CharSet::upperCase());
        $this->assertNotInSet($result, CharSet::digit());
        $this->assertNotInSet($result, CharSet::special());
        $this->assertNotInSet($result, CharSet::brackets());
    }

    public function testUpper()
    {
        $result = $this->random->generate(self::LENGTH, Random::UPPER_CASE);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertInSet($result, CharSet::upperCase());
        $this->assertNotInSet($result, CharSet::lowerCase());
        $this->assertNotInSet($result, CharSet::digit());
        $this->assertNotInSet($result, CharSet::special());
        $this->assertNotInSet($result, CharSet::brackets());
    }

    public function testLetter()
    {
        $result = $this->random->generate(self::LENGTH, CharSet::LETTER);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertInSet($result, CharSet::letter());
        $this->assertNotInSet($result, CharSet::digit());
        $this->assertNotInSet($result, CharSet::special());
        $this->assertNotInSet($result, CharSet::brackets());
    }

    public function testSpecial()
    {
        $result = $this->random->generate(self::LENGTH, CharSet::SPECIAL);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertInSet($result, CharSet::special());
        $this->assertNotInSet($result, CharSet::digit());
        $this->assertNotInSet($result, CharSet::letter());
        $this->assertNotInSet($result, CharSet::brackets());
    }

    public function testBrackets()
    {
        $result = $this->random->generate(self::LENGTH, CharSet::BRACKETS);
        $this->assertEquals(self::LENGTH, strlen($result));
        $this->assertInSet($result, CharSet::brackets());
        $this->assertNotInSet($result, CharSet::digit());
        $this->assertNotInSet($result, CharSet::letter());
        $this->assertNotInSet($result, CharSet::special());
    }

    private function assertInSet(string $value, array $set)
    {
        foreach ($this->makeIterable($value) as $char) {
            $this->assertContains($char, $set);
        }

    }

    private function assertNotInSet(string $value, array $set)
    {

        foreach ($this->makeIterable($value) as $char) {
            $this->assertNotContains($char, $set);
        }
    }

    /**
     * @param string $value
     * @return iterable
     * @throws \Exception
     */
    private function makeIterable(string $value)
    {
        if (is_string($value)) {
            return str_split($value);
        } else if (is_array($value)) {
            return $value;
        } elseif (is_scalar($value)) {
            return [$value];
        } elseif (is_iterable($value)) {
            /** @var iterable $value */
            return $value;
        }
        throw new \Exception('dafuque');
    }
}