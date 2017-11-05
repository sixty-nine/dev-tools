<?php

namespace SixtyNine\DevTools\Model;

class File
{
    /** @var Path */
    protected $path;
    /** @var string */
    protected $content;
    /** @var bool */
    protected $overwrite;

    public function __construct($path, $content = '', $overwrite = false)
    {
        if (!($path instanceof Path)) {
            $path = Path::parse($path);
        }

        $this->path = $path;
        $this->content = $content;
        $this->overwrite = $overwrite;
    }

    public static function create($path, $content = '', $overwrite = false)
    {
        return new self($path, $content, $overwrite);
    }

    /** @return string */
    public function getContent()
    {
        return $this->content;
    }

    /** @return Path */
    public function getPath()
    {
        return $this->path;
    }

    /** @return boolean */
    public function getOverwrite()
    {
        return $this->overwrite;
    }
}
