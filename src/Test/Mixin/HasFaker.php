<?php namespace Base10\Test\Mixin;

use Faker\Factory;
use Faker\Generator;

trait HasFaker
{

    /**
     * @var Generator
     */
    private $_faker;
    /**
     * @return Generator
     */
    public function faker()
    {
        return $this->_faker ?: $this->_faker = Factory::create();
    }

}