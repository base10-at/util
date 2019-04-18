<?php namespace Base10\Util;



use Symfony\Component\PropertyAccess\PropertyAccess;

class PropertyTranscriber
{
    private $accessor;

    /**
     * PropertyTranscriber constructor.
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param $from object
     * @param $to object
     * @param $fields string[]
     * @param bool $force
     * @return object
     */
    public function transcribe($from, $to, $fields, $force = false)
    {
        foreach ($fields as $fromProperty => $toProperty) {
            if (is_numeric($fromProperty)) {
                $fromProperty = $toProperty;
            }
            $isReadable = $this->accessor->isReadable($from, $fromProperty);
            $value = $this->accessor->getValue($from, $fromProperty);
            if ($force || $isReadable && !is_null($value)) {
                $this->accessor->setValue($to, $toProperty, $value);
            }
        }
        return $to;
    }
}