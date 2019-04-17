<?php namespace Base10\Util;

use Base10\Mixin\CanInitialise;

class Random
{
    use CanInitialise;
    /**
     * @deprecated
     */
    CONST HEX = 1;
    /**
     * @deprecated
     */
    CONST NUMBER = 2;
    /**
     * @deprecated
     */
    CONST LOWER_CASE = 4;
    /**
     * @deprecated
     */
    CONST UPPER_CASE = 8;

    function __construct()
    {
        $this->generateSeed();
    }

    public static function ALL()
    {
        return self::NUMBER |
            self::HEX |
            self::LOWER_CASE |
            self::UPPER_CASE;
    }

    public function generate($length, $flags = 15)
    {
        return $this->buildKey($length, $this->getCharacterSet($this->validateFlags($flags)));
    }

    public function charset($length, array $set)
    {
        return $this->buildKey($length, $set);
    }

    /**
     * @param $flags
     * @return array
     */
    private function getCharacterSet($flags): array
    {
        $charset = [];


        if ($flags & self::HEX) {
            $charset = array_merge($charset, range('a', 'f'), range(0, 9));
        } elseif ($flags & self::NUMBER) {
            $charset = array_merge($charset, range(0, 9));
        }
        if ($flags & self::LOWER_CASE) {
            $charset = array_merge($charset, range('a', 'z'));
        }
        if ($flags & self::UPPER_CASE) {
            $charset = array_merge($charset, range('A', 'Z'));
        }
        return $charset;
    }

    /**
     * @param int $flags
     * @return int
     */
    private function validateFlags(int $flags): int
    {
        if ($flags < 1 || !($flags & self::ALL())
        ) {
            throw new \InvalidArgumentException($flags . " is invalid");
        }
        return $flags;
    }

    private function generateSeed(): void
    {
        list($usec, $sec) = explode(' ', microtime());
        mt_srand((float)$sec + ((float)$usec * 100000));
    }

    /**
     * @param $length
     * @param $charset
     * @return string
     */
    private function buildKey($length, $charset): string
    {
        $count = count($charset);
        if(!$count || $count == 1 && $charset[0]==""){
            throw new \InvalidArgumentException("charset is empty");
        }
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $charset{mt_rand(0, count($charset) - 1)};
        }
        return $key;
    }
}