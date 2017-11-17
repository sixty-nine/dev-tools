<?php

namespace SixtyNine\DevTools\Tests;

use PHPUnit\Framework\TestCase;
use SixtyNine\DevTools\Model\Author;
use SixtyNine\DevTools\Model\Project;

class ProjectTest extends TestCase
{
    public function testFromComposerJson()
    {
        $proj = Project::fromComposerJson(__DIR__ . '/../../../../composer.json');
        $this->assertEquals('sixty-nine/dev-tools', $proj->getName());
        $this->assertEquals('MIT', $proj->getLicense());
        $this->assertEquals('Developer Tools', $proj->getDescription());
        $this->assertEquals('SixtyNine\DevTools', $proj->getNamespace());

        $authors = $proj->getAuthors();
        $this->assertTrue(is_array($authors));
        $this->assertCount(1, $authors);

        /** @var Author $author */
        $author = reset($authors);
        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('Daniele Barsotti', $author->getName());
        $this->assertEquals('hello@sixty-nine.ch', $author->getEmail());
        $this->assertNull($author->getHomepage());
        $this->assertNull($author->getRole());
    }
}
