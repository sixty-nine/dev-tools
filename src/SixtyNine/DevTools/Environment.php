<?php

namespace SixtyNine\DevTools;

use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem as BaseFileSystem;
use SixtyNine\DevTools\Model\Metadata;
use SixtyNine\DevTools\Model\Project;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Environment
{
    /** @var bool */
    protected $dryRun;
    /** @var string */
    protected $basePath;
    /** @var string */
    protected $dataPath;
    /** @var string */
    protected $templatesPath;
    /** @var string */
    protected $pathPrefix = '';
    /** @var Project */
    protected $project;
    /** @var \SixtyNine\DevTools\PathResolver */
    protected $resolver;
    /** @var \League\Flysystem\FilesystemInterface */
    protected $fs;
    /** @var \SixtyNine\DevTools\ConsoleIO */
    protected $io;
    /** @var string */
    protected $workingDir;

    function __construct($basePath, AdapterInterface $adapter, ConsoleIO $io, Project $project = null, $dryRun = true)
    {
        if ($adapter instanceof Local) {
            $this->pathPrefix = $adapter->getPathPrefix();
        }

        $this->basePath = $basePath;
        $this->dataPath = realpath(__DIR__ . '/../../data');
        $this->templatesPath = $this->dataPath . '/templates';

        $this->fs = new BaseFileSystem($adapter);
        $this->resolver = new PathResolver($this->pathPrefix, $basePath);
        $this->project = $project ?: new Project();
        $this->io = $io;
        $this->dryRun = $dryRun;
    }

    /** @return string */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /** @return string */
    public function getDataPath()
    {
        return $this->dataPath;
    }

    /** @return string */
    public function getTemplatesPath()
    {
        return $this->templatesPath;
    }

    /** @return \SixtyNine\DevTools\Model\Project */
    public function getProject()
    {
        return $this->project;
    }

    /** @return \League\Flysystem\FilesystemInterface */
    public function getFs()
    {
        return $this->fs;
    }

    /** @return \SixtyNine\DevTools\PathResolver */
    public function getResolver()
    {
        return $this->resolver;
    }

    /** @return string */
    public function getPathPrefix()
    {
        return $this->pathPrefix;
    }

    /** @return ConsoleIO */
    public function getIo()
    {
        return $this->io;
    }

    /** @return boolean */
    public function isDryRun()
    {
        return $this->dryRun;
    }

    /**
     * @param string $basePath
     * @return Environment
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
        return $this;
    }

    /**
     * @param string $dataPath
     * @return Environment
     */
    public function setDataPath($dataPath)
    {
        $this->dataPath = $dataPath;
        return $this;
    }

    /**
     * @param string $templatesPath
     * @return Environment
     */
    public function setTemplatesPath($templatesPath)
    {
        $this->templatesPath = $templatesPath;
        return $this;
    }

    /**
     * @param \SixtyNine\DevTools\Model\Project $project
     * @return Environment
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    public function getCwd()
    {
        return $this->workingDir;
    }

    public function cd($dir)
    {
        $newDir = $this->workingDir . $dir;
        if (!is_dir($newDir)) {
            throw new \InvalidArgumentException('Invalid directory: ' . $newDir);
        }

        // TODO: check we don't exit the base dir

        $this->workingDir = $newDir;
    }
}
