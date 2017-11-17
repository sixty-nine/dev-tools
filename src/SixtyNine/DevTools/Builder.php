<?php

namespace SixtyNine\DevTools;

use SixtyNine\DevTools\Model\File;
use SixtyNine\DevTools\Model\Path;
use SixtyNine\DevTools\Model\Project;
use SixtyNine\DevTools\Model\Author;
use SixtyNine\DevTools\Tasks\ComposerInstall;
use SixtyNine\DevTools\Tasks\CreateDir;
use SixtyNine\DevTools\Tasks\CreateFile;
use SixtyNine\DevTools\Tasks\GitCheckout;
use SixtyNine\DevTools\Tasks\MakeWritable;
use SixtyNine\DevTools\Tasks\RenderTemplate;
use SixtyNine\DevTools\Tasks\RunProcess;
use SixtyNine\DevTools\Tasks\Task;

class Builder
{
    /** @var Environment */
    protected $env;

    /**
     * @param Environment $env
     */
    public function __construct(Environment $env)
    {
        $this->env = $env;
        $this->workingDir = $env->getBasePath();

        if ($this->env->isDryRun()) {
            $this->env->getIo()->writeln('<question>Running in dry mode</question>');
        } else {
            $this->env->getIo()->writeln('<error>Running in real mode</error>');
        }
    }

    /**
     * @param Author $vendor
     * @return $this
     */
    public function setVendor(Author $vendor)
    {
        $this->env->getMetadata()->setVendor($vendor);
        return $this;
    }

    /**
     * @param Project $project
     * @return $this
     */
    public function setProject(Project $project)
    {
        $this->env->getMetadata()->setProject($project);
        return $this;
    }

    /**
     * @param File $file
     * @return $this
     */
    public function createFile(File $file)
    {
        $task = new CreateFile($this->env, $file);
        $task->execute();
        return $this;
    }

    /**
     * @param \SixtyNine\DevTools\Model\Path $path
     * @return $this
     */
    public function createDirectory(Path $path)
    {
        $task = new CreateDir($this->env, $path);
        $task->execute();
        return $this;
    }

    /**
     * @param \SixtyNine\DevTools\Model\Path $path
     * @return $this
     */
    public function makeWritable(Path $path)
    {
        $task = new MakeWritable($this->env, $path);
        $task->execute();
        return $this;
    }

    /**
     * @param Path $path
     * @param string $url
     * @return $this
     */
    public function gitCheckout(Path $path, $url, $checkoutName = '')
    {
        $task = new GitCheckout($this->env, $path, $url, $checkoutName);
        $task->execute();
        return $this;
    }

    /**
     * @param Path $path
     * @return $this
     */
    public function composerInstall(Path $path)
    {
        $task = new ComposerInstall($this->env, $path);
        $task->execute();
        return $this;
    }

    /**
     * @param string $template
     * @param \SixtyNine\DevTools\Model\Path $destination
     * @param array $params
     * @return $this
     */
    public function renderTemplate($template, Path $destination, $params = [])
    {
        $task = new RenderTemplate($this->env, $template, $destination, $params);
        $task->execute();
        return $this;
    }

    /**
     * @param string $dir
     */
    public function cd($dir)
    {
        $this->env->cd($dir);
    }

    /**
     * @param Task|array[Task] $tasks
     * @return $this
     */
    public function executeTasks($tasks)
    {
        $tasks = is_array($tasks) ? $tasks : [$tasks];
        /** @var Task $task */
        foreach ($tasks as $task) {
            $task->execute();
        }
        return $this;
    }

    public function runProcess($path, $cmd)
    {
        $task = new RunProcess($this->env, Path::create($path), $cmd);
        $task->execute();
        return $this;
    }
}
