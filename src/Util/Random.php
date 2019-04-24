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

    public function generate($length, $flags = 15)
    {
        return $this->buildKey($length, CharSet::getSet(CharSet::validateFlags($flags)));
    }

    public function charset($length, array $set)
    {
        return $this->buildKey($length, $set);
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