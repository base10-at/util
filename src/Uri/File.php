<?php namespace Base10\Uri;

use Base10\Mixin\CanInitialise;

/**
 * Class File
 * @package Base10\Uri
 */
class File
{


    use CanInitialise;
    private $path = [];

    private static $projectDir;

    private function __construct(... $path)
    {
        $this->_push(... $path);
    }


    /**
     * @return self | string
     */
    public static function getProjectDir()
    {
        if (null === self::$projectDir) {
            $isTest = (isset($_ENV['BASE10_ENV']) && $_ENV['BASE10_ENV'] == "base10/util");

            $dir = __DIR__;
            while (!file_exists($dir . '/composer.json') ||
                (!$isTest && file_exists($dir . '/.b10-util'))
            ) {
                $dir = dirname($dir);
            }
            self::$projectDir = $dir;
        }
        return self::split(self::$projectDir);
    }

    public static function split(string $path)
    {
        $parts = explode(DIRECTORY_SEPARATOR, $path);

        if ($parts[0] === '') {
            if (count($parts) > 1) {
                array_shift($parts);
            }
            $parts[0] = '/' . $parts[0];
        }
        return self::init(...$parts);
    }

    /**
     * @return self | string
     */
    public static function getPublicDir(string ...$path)
    {
        $instance = self::getProjectDir()->push('public');
        if (count($path)) {
            array_push($instance->path, ... $path);
        }
        return $instance;
    }

    /**
     * @param int $mode
     * @return static
     */
    public function create($mode = 0777)
    {
        $path = $this . "";
        if (!file_exists($path)) {
            mkdir($path, $mode, true);
        }
        return $this;
    }

    /**
     * @return static
     */
    public function copy()
    {
        $instance = new static();
        $instance->path = $this->path;
        return $instance;
    }

    /**
     * @param \string[] $path
     * @return self | string
     */
    public function push(string ...$path): self
    {
        return $this->copy()->_push(... $path);
    }

    /**
     * @param \string[] $path
     * @return self | string
     */
    private function _push(string ...$path): self
    {

        if (count($path)) {
            array_push($this->path, ... $path);
        }
        return $this;
    }


    public function __toString()
    {
        return $this->normalize();
    }

    public function normalize(): string
    {
        return $this->buildPath();

    }


    /**
     * @return string
     */
    private function buildPath(): string
    {
        return implode(DIRECTORY_SEPARATOR, array_values(array_filter($this->path)));
    }
}