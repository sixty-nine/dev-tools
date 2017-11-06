<?php

namespace SixtyNine\DevTools;

use SixtyNine\DevTools\Model\Path;

class PathResolver
{
    /** @var string */
    protected $basePath;
    /** @var string */
    protected $pathPrefix;

    /**
     * @param string $pathPrefix
     * @param string $basePath
     */
    function __construct($pathPrefix = '', $basePath = '')
    {
        $this->pathPrefix = $this->normalize($pathPrefix);
        $this->basePath = $this->normalize($basePath);
    }

    public function resolve(Path $path, $usePrefix = true)
    {
        $normalized = $this->normalize($path->getPath());

        $fullPath = '';

        if ($usePrefix && $this->pathPrefix != '/') {
            $fullPath .= $this->pathPrefix;
        }

        if (!$path->isAbsolute() && $this->basePath !== '/') {
            $fullPath .= $this->basePath;
        }

        return sprintf('%s%s', $fullPath, $normalized);
    }

    public function normalize($path)
    {
        return substr($path, 0, 1) === '/' ? $path : '/' . $path;
    }
} 