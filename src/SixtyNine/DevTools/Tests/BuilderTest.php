<?php

namespace SixtyNine\DevTools\Tests;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;
use SixtyNine\DevTools\Builder;
use SixtyNine\DevTools\ConsoleIO;
use SixtyNine\DevTools\Environment;
use SixtyNine\DevTools\Model\File;
use SixtyNine\DevTools\Model\Project;
use SixtyNine\DevTools\Model\Path;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\StringInput;

class BuilderTest extends TestCase
{
    /** @var Filesystem */
    protected $fs;
    /** @var Builder */
    protected $builder;
    /** @var Environment */
    protected $env;
    /** @var string */
    protected $testPath;

    public function setUp()
    {
        $this->fs = new Filesystem(new Local('/'));
        $this->testPath = '/tmp/' . uniqid('test_') . '/';
        $adapter = new Local($this->testPath, LOCK_EX, Local::DISALLOW_LINKS, [
            'file' => [
                'writable' => 0777
            ],
            'dir' => [
                'writable' => 0755
            ]
        ]);
        $this->fs->createDir($this->testPath);

        $this->env = new Environment('/', $adapter, new ConsoleIO(new StringInput(''), new NullOutput()), new Project(), false);
        $this->builder = new Builder($this->env);
    }

    public function tearDown()
    {
        $this->fs->deleteDir($this->testPath);
    }

    public function testCreateFile()
    {
        $this->builder->createFile(File::create('baz/hello', 'hello world'));
        $this->assertTestFileContent('hello world', '/baz/hello');
    }

    public function testCreateFileExisting()
    {
        $this->builder->createFile(File::create('baz/hello', 'content 1'));
        $this->builder->createFile(File::create('baz/hello', 'content 2'));
        $this->assertTestFileContent('content 1', '/baz/hello');
    }

    public function testCreateFileExistingOverwrite()
    {
        $this->builder->createFile(File::create('baz/hello', 'content 1'));
        $this->builder->createFile(File::create('baz/hello', 'content 2', true));
        $this->assertTestFileContent('content 2', '/baz/hello');
    }

    public function testCreateDirectory()
    {
        $this->builder->createDirectory(Path::parse('/foo/bar'));
        $this->assertDirectoryExists($this->testPath . '/foo');
        $this->assertDirectoryExists($this->testPath . '/foo/bar');
    }

    public function testMakeWritable()
    {
        $this->builder->createFile(File::create('/foo/bar/baz', 'content'));

        $this->fs->setVisibility($this->testPath . '/foo', 'private');
        $this->fs->setVisibility($this->testPath . '/foo/bar', 'private');
        $this->fs->setVisibility($this->testPath . '/foo/bar/baz', 'private');
        $this->assertVisibility('private', '/foo');
        $this->assertVisibility('private', '/foo/bar');
        $this->assertVisibility('private', '/foo/bar/baz');

        $this->builder->makeWritable(Path::parse('/foo'));
        $this->assertVisibility('public', '/foo');
        $this->assertVisibility('public', '/foo/bar');
        $this->assertVisibility('public', '/foo/bar/baz');
    }

    protected function assertTestFileContent($expectedContent, $filename)
    {
        $filename = $this->testPath . $filename;
        $this->assertFileExists($filename);
        $this->assertEquals($expectedContent, file_get_contents($filename));
    }

    protected function assertVisibility($visibility, $filename)
    {
        $this->assertEquals($visibility, $this->fs->getVisibility($this->testPath . $filename));
    }
}