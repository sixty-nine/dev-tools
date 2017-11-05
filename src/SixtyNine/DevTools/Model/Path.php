<?php

namespace SixtyNine\DevTools\Model;

class Path
{
    /** @var string */
    protected $path;
    /** @var bool */
    protected $absolute;

    /**
     * @param string $path
     * @param bool $absolute
     */
    public function __construct($path, $absolute = false)
    {
        $this->path = $path;
        $this->absolute = $absolute;
    }

    /**
     * @param string $path
     * @param bool $absolute
     * @return Path
     */
    public static function create($path, $absolute = false)
    {
        return new self($path, $absolute);
    }

    /**
     * @param string $path
     * @return Path
     */
    public static function parse($path)
    {
        return self::create($path, substr($path, 0, 1) === '/');
    }

    /** @return bool */
    public function isAbsolute()
    {
        return $this->absolute;
    }

    /** @return string */
    public function getPath()
    {
        return $this->path;
    }
}
