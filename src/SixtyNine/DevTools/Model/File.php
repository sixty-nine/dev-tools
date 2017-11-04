<?php

namespace SixtyNine\DevTools\Model;

class File
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $content;
    /** @var bool */
    protected $overwrite;

    public function __construct($name, $content = '', $overwrite = false)
    {
        $this->name = $name;
        $this->content = $content;
        $this->overwrite = $overwrite;
    }

    /** @return string */
    public function getContent()
    {
        return $this->content;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    /** @return boolean */
    public function getOverwrite()
    {
        return $this->overwrite;
    }
}
