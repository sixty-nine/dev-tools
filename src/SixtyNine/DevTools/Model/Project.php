<?php

namespace SixtyNine\DevTools\Model;

class Project
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $license;

    /**
     * @param string $name
     * @param string $license
     * @param string $namespace
     */
    public function __construct($name = '', $license = '')
    {
        $this->name = $name;
        $this->license = $license;
    }

    /**
     * @return Project
     */
    public static function create()
    {
        return new self;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $license
     * @return $this
     */
    public function setLicense($license)
    {
        $this->license = $license;
        return $this;
    }

    /** @return string */
    public function getLicense()
    {
        return $this->license;
    }
}
