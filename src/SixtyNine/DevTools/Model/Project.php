<?php

namespace SixtyNine\DevTools\Model;

class Project
{
    /** @var string */
    protected $basePath;
    /** @var array */
    protected $directories = [];
    /** @var array */
    protected $files = [];
    /** @var array */
    protected $writableDirs = [];
    /** @var array */
    protected $gitCheckouts = [];

    /**
     * @param string $name
     * @param string $basePath
     */
    public function __construct($name, $basePath)
    {
        $this->name = $name;
        $this->basePath = $basePath;
        $this->directories[] = $basePath;
    }

    /**
     * @param string $name
     * @param string $content
     * @param bool $absolute
     * @return $this
     */
    public function createFile($name, $content, $absolute = false, $overwrite = false)
    {
        $filename = $this->buildPath($name, $absolute);
        $file = new File($filename, $content, $overwrite);
        $this->files[] = $file;

        if (!$absolute) {
            $path = dirname($filename);
            if (!in_array($path, $this->directories)) {
                $this->directories[] = $path;
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function createDirectory($dirName)
    {
        if (!in_array($dirName, $this->directories)) {
            $this->directories[] = $dirName;
        }

        return $this;
    }

    /**
     * @param string $dirName
     * @return $this
     */
    public function makeWritable($dirName)
    {
        if (!in_array($dirName, $this->writableDirs)) {
            $this->writableDirs[] = $dirName;
        }

        return $this;
    }

    public function gitCheckout($path, $url)
    {
        $this->gitCheckouts[$path] = $url;
        return $this;
    }

    /** @return string */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /** @return array */
    public function getFiles()
    {
        return $this->files;
    }

    /** @return array */
    public function getDirectories()
    {
        return $this->directories;
    }

    /** @return array */
    public function getWritableDirectories()
    {
        return $this->writableDirs;
    }

    /** @return array */
    public function getGitCheckouts()
    {
        return $this->gitCheckouts;
    }

    /**
     * @param string $name
     * @param bool $absolute
     * @return string
     */
    public function buildPath($name, $absolute = false)
    {
        return $absolute ? $name : sprintf('%s/%s', $this->basePath, substr($name, 0, 1) !== '/' ? $name : substr($name, 1));
    }
}
