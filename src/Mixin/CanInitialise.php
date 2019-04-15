<?php namespace Base10\Mixin;

/**
 * Class CanInitialise
 * @package Base10\Mixin
 */
trait CanInitialise
{
    /**
     * @return static
     */
    public static function init(...$args)
    {
        return new static(...$args);
    }
}