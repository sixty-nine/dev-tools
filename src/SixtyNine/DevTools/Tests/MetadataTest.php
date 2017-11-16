<?php

namespace SixtyNine\DevTools\Tests;

use PHPUnit\Framework\TestCase;
use SixtyNine\DevTools\Model\Metadata;

class MetadataTest extends TestCase
{
    public function testFromComposerJson()
    {
        $meta = Metadata::fromComposerJson(__DIR__ . '/../../../../composer.json');
        $this->assertEquals('sixty-nine', $meta->getVendor()->getName());
        $this->assertEquals('dev-tools', $meta->getProject()->getName());
    }
}