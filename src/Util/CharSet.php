<?php
/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.19
 * Time: 23:09
 */

namespace Base10\Util;


class CharSet
{
    CONST HEX = 1;
    CONST NUMBER = 2;
    CONST LOWER_CASE = 4;
    CONST UPPER_CASE = 8;


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