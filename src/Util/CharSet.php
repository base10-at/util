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
    CONST SPECIAL = 16;
    CONST BRACKETS = 32;

    CONST LETTER =
        self::LOWER_CASE |
        self::UPPER_CASE;

    CONST ALL =
        self::HEX |
        self::NUMBER |
        self::LOWER_CASE |
        self::UPPER_CASE |
        self::SPECIAL |
        self::BRACKETS;


    private function __construct()
    {
    }

    /**
     * @return array
     */
    public static function lowerCase()
    {
        return range('a', 'z');
    }

    /**
     * @param $flags
     * @return array
     */
    public static function getSet($flags): array
    {
        $charset = [];


        if ($flags & CharSet::HEX) {
            $charset = array_merge($charset, self::hex());
        } elseif ($flags & CharSet::NUMBER) {
            $charset = array_merge($charset, self::digit());
        }
        if ($flags & CharSet::LOWER_CASE) {
            $charset = array_merge($charset, self::lowerCase());
        }
        if ($flags & CharSet::UPPER_CASE) {
            $charset = array_merge($charset, self::upperCase());
        }
        if ($flags & CharSet::SPECIAL) {
            $charset = array_merge($charset, self::special());
        }
        if ($flags & CharSet::BRACKETS) {
            $charset = array_merge($charset, self::brackets());
        }
        return array_unique($charset);
    }

    /**
     * @return array
     */
    public static function upperCase()
    {
        return range('A', 'Z');
    }

    /**
     * @return array
     */
    public static function digit()
    {
        return str_split(implode('', range('0', '9')));
    }

    /**
     * @return array
     */
    public static function hex()
    {
        return array_merge(range('a', 'f'), self::digit());
    }

    /**
     * @return array
     */
    public static function letter()
    {
        return array_merge(self::upperCase(), self::lowerCase());
    }

    /**
     * @return array
     */
    public static function special()
    {
        return ['$', '/', '*', '#', '?', '.', '+', '-', '~', '%', '\\', '=', '<', '>'];
    }

    /**
     * @return array
     */
    public static function brackets()
    {
        return ['(', ')', '[', ']', '{', '}'];
    }
}