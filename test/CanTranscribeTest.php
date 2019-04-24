<?php


namespace AppTest\Util;


use Base10\Mixin\CanTranscribe;
use Base10\Test\Mixin\HasFaker;
use PHPUnit\Framework\TestCase;

class CanTranscribeTest extends TestCase
{
    use HasFaker;
    use CanTranscribe;

    public function testTranscribe()
    {
        $values = array_map(function ($x) {
            return rand(1, 100);
        }, array_fill(0, 10, 0));

        $srcs = [
            new class()
            {
                public $a;
            },
            new class()
            {
                private $a;

                /**
                 * @return mixed
                 */
                public function getA()
                {
                    return $this->a;
                }

                /**
                 * @param mixed $a
                 * @return
                 */
                public function setA($a)
                {
                    $this->a = $a;
                    return $this;
                }

            },
        ];

        $dsts = [new class()
        {
            public $a;
        },
            new class()
            {
                private $a;

                /**
                 * @return mixed
                 */
                public function getA()
                {
                    return $this->a;
                }

                /**
                 * @param mixed $a
                 * @return
                 */
                public function setA($a)
                {
                    $this->a = $a;
                    return $this;
                }

            },];


        $target = new class()
        {
            public $a;
        };

        foreach ($values as $value) {
            $this->assertNotEquals(0, $value);
            $first = new class($value)
            {
                public $a;

                /**
                 *  constructor.
                 * @param $a
                 */
                public function __construct($a)
                {
                    $this->a = $a;
                }


            };
            $this->assertEquals($value, $first->a);
            foreach ($srcs as $src) {
                $this->transcribeProperty($first, $src, 'a');
                foreach ($dsts as $dst) {
                    $this->transcribe($src, $dst, ['a']);
                    $this->transcribeProperty($dst, $target, 'a');
                    $this->assertEquals($value, $target->a);
                }
            }
        }
    }


}