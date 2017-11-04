<?php

namespace SixtyNine\DevTools;

use League\Flysystem\Adapter\Local;
use League\Flysystem\FilesystemInterface;
use SixtyNine\DevTools\Model\File;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Process\Process;

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
            try {
                $this->fs->setVisibility($path, 'writable');
            } catch (\LogicException $ex) {
                // The filesystem does not support visibility, do nothing...
            }
        }
    }

    public function gitCheckout($path, $url)
    {
        $cmd = sprintf('git clone %s', $url);
        $this->runProcess($path, $cmd);

//        $gitDir = str_replace('.git', '', basename($url));
//        $gitDir = sprintf('%s/%s', $path, $gitDir);
//        $composerJson = sprintf('%s/composer.json', $gitDir);
//        // TODO: does not work. The flysystem path does not match the real filesystem path
//        // TODO: introduce the notion of base path in this class (move the flysystem adapter here)
//        var_dump($composerJson);
//        if ($this->fs->has($composerJson)) {
//            var_dump('yes');
//            $this->composerInstall($gitDir);
//        }
    }

    public function composerInstall($path)
    {
        $this->runProcess($path, 'composer install');
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
