<?php

namespace SixtyNine\DevTools\Tests;

use PHPUnit\Framework\TestCase;
use SixtyNine\DevTools\Model\Path;
use SixtyNine\DevTools\PathResolver;

class PathResolverTest extends TestCase
{
    public function testResolverWithoutPrefix()
    {
        $resolver = new PathResolver('', '/root');
        $this->assertEquals('/root/foo/bar', $resolver->resolve(new Path('/foo/bar'), false));
        $this->assertEquals('/foo/bar', $resolver->resolve(new Path('/foo/bar', true), false));
        $this->assertEquals('/root/foo/bar', $resolver->resolve(new Path('/foo/bar')));
        $this->assertEquals('/foo/bar', $resolver->resolve(new Path('/foo/bar', true)));

        $this->assertEquals('/root/foobar', $resolver->resolve(new Path('foobar')));
        $this->assertEquals('/foobar', $resolver->resolve(new Path('foobar', true)));
        $this->assertEquals('/root/foobar', $resolver->resolve(new Path('foobar'), false));
        $this->assertEquals('/foobar', $resolver->resolve(new Path('foobar', true), false));
    }

    public function testResolverWithPrefix()
    {
        $resolver = new PathResolver('/prefix', '/root');
        $this->assertEquals('/root/foo/bar', $resolver->resolve(new Path('/foo/bar'), false));
        $this->assertEquals('/foo/bar', $resolver->resolve(new Path('/foo/bar', true), false));
        $this->assertEquals('/prefix/root/foo/bar', $resolver->resolve(new Path('/foo/bar')));
        $this->assertEquals('/prefix/foo/bar', $resolver->resolve(new Path('/foo/bar', true)));


        $this->assertEquals('/prefix/root/foobar', $resolver->resolve(new Path('foobar')));
        $this->assertEquals('/prefix/foobar', $resolver->resolve(new Path('foobar', true)));
        $this->assertEquals('/root/foobar', $resolver->resolve(new Path('foobar'), false));
        $this->assertEquals('/foobar', $resolver->resolve(new Path('foobar', true), false));
    }
}