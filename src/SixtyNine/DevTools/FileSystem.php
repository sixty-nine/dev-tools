<?php

namespace SixtyNine\DevTools;

use League\Flysystem\Adapter\Local;
use League\Flysystem\FilesystemInterface;
use SixtyNine\DevTools\Model\File;
use Symfony\Component\Console\Output\Output;

class FileSystem
{
    /** @var \League\Flysystem\FilesystemInterface */
    protected $fs;
    /** @var \Symfony\Component\Console\Output\Output */
    protected $output;
    /** @var bool */
    protected $dryRun;

    /**
     * @param FilesystemInterface $fs
     * @param \Symfony\Component\Console\Output\Output $output
     * @param bool $dryRun
     */
    public function __construct(FilesystemInterface $fs, Output $output, $dryRun = true)
    {
        $this->fs = $fs;
        $this->output = $output;
        $this->dryRun = $dryRun;

        if ($this->dryRun) {
            $this->output->writeln('<question>Running in dry mode</question>');
        } else {
            $this->output->writeln('<error>Running in real mode</error>');
        }
    }

    /**
     * @param File $file
     */
    public function createFile(File $file)
    {
        if (!$file->getOverwrite() && $this->fs->has($file->getName())) {
            $this->output->writeln('<error>File already exist, no overwrite</error>');
            return;
        }

        $this->output->writeln(sprintf('Create file <info>%s</info>', $file->getName()));
        if (!$this->dryRun) {
            $this->fs->put($file->getName(), $file->getContent());
        }

        $this->output->writeln(sprintf('<comment>%s</comment>', $file->getContent()));
    }

    /**
     * @param string $path
     */
    public function createDirectory($path)
    {
        $this->output->writeln(sprintf('Create directory <info>%s</info>', $path));
        if (!$this->dryRun) {
            $this->fs->createDir($path);
        }
    }

    /**
     * @param string $path
     * @param bool $silent
     */
    public function makeWritable($path, $silent = false)
    {
        if (!$silent) {
            $this->output->writeln(sprintf('Make <info>%s</info> writable', $path));
        }

        if (!$this->dryRun) {
            $object = $this->fs->get($path);
            if ($object->isDir()) {
                foreach ($this->fs->listContents($path) as $file) {
                    $this->makeWritable($file['path'], true);
                }
            }
            @$this->fs->setVisibility($path, 'writable');
        }
    }
}
