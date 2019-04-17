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

            $r = new \ReflectionClass(self::class);
            $dir = $rootDir = \dirname($r->getFileName());
            while (!file_exists($dir . '/composer.json') ||
                (!$isTest && file_exists($dir . '/.b10-util'))
            ) {
                if ($dir === \dirname($dir)) {
                    self::$projectDir = $rootDir;
                    return self::init(self::$projectDir);
                }
                $dir = \dirname($dir);
            }
            self::$projectDir = $dir;
        }
        return self::init(self::$projectDir);
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
        return implode(DIRECTORY_SEPARATOR, $this->path);
    }
}