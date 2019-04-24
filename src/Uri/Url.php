<?php namespace Base10\Uri;

use Base10\Mixin\CanInitialise;

/**
 * Class Url
 * @package Base10\Uri
 * @method static static api(...$path) callStatic
 */
class Url
{
    use CanInitialise;


    private $path = [];
    private $params = [];


    /**
     * @param \string[] $path
     * @return Url
     */
    public function push(string ...$path): Url
    {
        return $this->copy()->_push(... $path);
    }

    /**
     * @param array $params
     * @return Url
     */
    public function putParams(array $params): Url
    {
        return $this->copy()->_putParams($params);
    }


    /**
     * @param $key
     * @param $value
     * @return Url
     */
    public function put($key, $value): Url
    {
        return $this->putParam($key, $value);
    }

    /**
     * @param $key
     * @param $value
     * @return Url
     */
    public function putParam($key, $value): Url
    {
        return $this->putParams([$key => $value]);
    }

    public function __toString()
    {
        return $this->normalize();
    }

    public function normalize(): string
    {
        return $this->_buildPath() . $this->_buildParams();

    }

    /*
     * =========================================
     * protected functions
     * =========================================
     */

    /**
     * @param \string[] $path
     * @return Url
     */
    protected function _push(string ...$path): Url
    {
        if (count($path)) {
            array_push($this->path, ... $path);
        }
        return $this;
    }

    /**
     * @param array $param
     * @return Url
     */
    public function _putParams(array $param): Url
    {
        $this->params += $param;
        return $this;
    }

    /*
     * =========================================
     * private functions
     * =========================================
     */
    private function _buildParams()
    {
        if (!count($this->params)) {
            return "";
        }
        $arr = [];
        foreach ($this->params as $k => $element) {
            $arr [] = urlencode($k) . '=' . urlencode($element);
        }
        return '?' . implode('&', $arr);
    }

    /**
     * @return string
     */
    private function _buildPath(): string
    {

        $mapped = $this->_sanitize();


        return implode('/', $mapped);
    }


    /*
     * =========================================
     * construct functions
     * =========================================
     */

    function __construct(string ...$path)
    {
        $this->_push(... $path);

    }

    /**
     * @param $name
     * @param $arguments
     * @return static
     * @return static
     */
    public static function __callStatic($name, $arguments)
    {
        return static::init($name, ...$arguments);
    }

    /**
     * @return static
     */
    public function copy()
    {
        $instance = new static();
        $instance->path = $this->path;
        $instance->params = $this->params;
        return $instance;
    }

    private function _copyArray($array): array
    {
        return $array;
    }

    /**
     * @return array
     */
    private function _sanitize(): array
    {

        $mapped = [];
        $path = $this->_copyArray($this->path);
        if (count($path)) {

            $first = array_shift($path);
            $match = preg_match("/https?:\/\/(.*)/", $first);
            if(!$match){
                $first = urlencode($first);
            }
            foreach ($path as $element) {
                $mapped[] = urlencode($element);
            }
            array_unshift($mapped, $first);
        }

        return $mapped;
    }

}