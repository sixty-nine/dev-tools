<?php

namespace SixtyNine\DevTools;

use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem as BaseFileSystem;
use SixtyNine\DevTools\Model\File;
use SixtyNine\DevTools\Model\Path;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class Builder
{
    /** @var string */
    protected $pathPrefix = '';
    /** @var string */
    protected $basePath = '';
    /** @var \League\Flysystem\FilesystemInterface */
    protected $fs;
    /** @var \Symfony\Component\Console\Output\OutputInterface */
    protected $output;
    /** @var bool */
    protected $dryRun;
    /** @var \SixtyNine\DevTools\PathResolver */
    protected $resolver;

    /**
     * @param string $basePath
     * @param AdapterInterface $adapter
     * @param OutputInterface $output
     * @param bool $dryRun
     */
    public function __construct($basePath, AdapterInterface $adapter, OutputInterface $output, $dryRun = true)
    {
        if ($adapter instanceof Local) {
            $this->pathPrefix = $adapter->getPathPrefix();
        }

        $this->basePath = $basePath;
        $this->resolver = new PathResolver($this->pathPrefix, $basePath);
        $this->fs = new BaseFileSystem($adapter);
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
     * @return $this
     */
    public function createFile(File $file)
    {
        $filename = $this->resolver->resolve($file->getPath(), false);
        if (!$file->getOverwrite() && $this->fs->has($filename)) {
            $this->output->writeln('<error>File already exist, no overwrite</error>');
            return $this;
        }

        $this->output->writeln(sprintf('Create file <info>%s</info>', $filename));
        if (!$this->dryRun) {
            $this->fs->put($filename, $file->getContent());
        }

        $this->output->writeln(sprintf('<comment>%s</comment>', $file->getContent()));

        return $this;
    }

    /**
     * @param \SixtyNine\DevTools\Model\Path $path
     * @return $this
     */
    public function createDirectory(Path $path)
    {
        $filename = $this->resolver->resolve($path, false);
        $this->output->writeln(sprintf('Create directory <info>%s</info>', $filename));
        if (!$this->dryRun) {
            $this->fs->createDir($filename);
        }
        return $this;
    }

    /**
     * @param \SixtyNine\DevTools\Model\Path $path
     * @param bool $silent
     * @return $this
     */
    public function makeWritable(Path $path, $silent = false)
    {
        $filename = $this->resolver->resolve($path, false);

        if (!$silent) {
            $this->output->writeln(sprintf('Make <info>%s</info> writable', $filename));
        }

        if (!$this->dryRun) {
            $object = $this->fs->get($filename);
            if ($object->isDir()) {
                foreach ($this->fs->listContents($filename) as $file) {
                    $this->makeWritable(Path::create($file['path'], true), false);
                }
            }

            try {
                $this->fs->setVisibility($filename, 'writable');
            } catch (\LogicException $ex) {
                // The filesystem does not support visibility, do nothing...
            }
        }
        return $this;
    }

    /**
     * @param Path $path
     * @param string $url
     * @return $this
     */
    public function gitCheckout(Path $path, $url)
    {
        $realPath = $this->resolver->resolve($path, true);
        var_dump($path, $realPath);
        $cmd = sprintf('git clone %s', $url);
        $this->runProcess($realPath, $cmd);

        $gitDir = str_replace('.git', '', basename($url));
        $gitDir = sprintf('%s/%s', $path->getPath(), $gitDir);
        $composerJson = sprintf('%s/composer.json', $gitDir);
        if ($this->fs->has($composerJson)) {
            $this->composerInstall(Path::create($gitDir, true));
        }
        return $this;
    }

    public function composerInstall(Path $path)
    {
        $realPath = $this->resolver->resolve($path, true);
        $this->runProcess($realPath, 'composer install');
        return $this;
    }

    protected function runProcess($path, $cmd)
    {
        $this->output->writeln(sprintf('Running <info>%s</info> in <info>%s</info>', $cmd, $path));

        if ($this->dryRun) {
            return;
        }

        $process = new Process($cmd, $path);
        $process->start();

        foreach ($process as $data) {
            $this->output->writeln(sprintf('<comment>%s</comment>', $this->normalizeOutput($data)));
        }

        if ($process->isSuccessful()) {
            $this->output->writeln('<info>Done</info>');
        } else {
            $this->output->writeln('<error>Failed</error>');
        }
    }

    protected function normalizeOutput($output)
    {
        return substr($output, -1) === PHP_EOL ? substr($output, 0, -1) : $output;
    }
}
