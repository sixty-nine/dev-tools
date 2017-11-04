<?php

namespace SixtyNine\DevTools\Builder;

use SixtyNine\DevTools\FileSystem;
use SixtyNine\DevTools\Model\Project;

class ProjectBuilder
{
    /** @var \SixtyNine\DevTools\Model\Project  */
    protected $project;
    /** @var \SixtyNine\DevTools\FileSystem */
    protected $filesystem;

    /**
     * @param Project $project
     */
    public function __construct(Project $project, FileSystem $filesystem)
    {
        $this->project = $project;
        $this->filesystem = $filesystem;
    }

    public function build()
    {
        foreach ($this->project->getDirectories() as $directory) {
            $this->filesystem->createDirectory($directory);
        }

        /** @var \SixtyNine\DevTools\Model\File $file */
        foreach ($this->project->getFiles() as $file) {
            $this->filesystem->createFile($file);
        }

        foreach ($this->project->getWritableDirectories() as $directory) {
            $this->filesystem->makeWritable($directory);
        }

        foreach ($this->project->getGitCheckouts() as $path => $url) {
            $this->filesystem->gitCheckout($path, $url);
        }
    }
}
