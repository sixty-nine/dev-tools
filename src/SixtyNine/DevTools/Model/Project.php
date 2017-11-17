<?php

namespace SixtyNine\DevTools\Model;

use Webmozart\Assert\Assert;

class Project
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $description;
    /** @var string */
    protected $license;
    /** @var Author[]  */
    protected $authors = [];
    /** @var array */
    protected static $validLicenses = [
        'bsd-2-clause', 'gpl-3.0', 'mpl-2.0', 'lgpl-3.0', 'bsd-3-clause',
        'apache-2.0', 'mit', 'gpl-2.0', 'agpl-3.0', 'epl-1.0', 'unlicense',
        'lgpl-2.1',
    ];

    /**
     * @param string $name
     * @param string $description
     * @param string|null $license
     */
    function __construct($name = '', $description = '', $license = null)
    {
        $this->description = $description;
        $this->license = $license;
        $this->name = $name;
    }

    /**
     * @param string $name
     * @param string $description
     * @param null $license
     * @return Project
     */
    public static function create($name = '', $description = '', $license = null)
    {
        return new self($name, $description, $license);
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setName($name)
    {
        if (!self::isValidName($name)) {
            throw new \InvalidArgumentException("Invalid project name '$name'");
        }

        $this->name = $name;
        return $this;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $license
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setLicense($license)
    {
        if (!self::isValidLicense($license)) {
            throw new \InvalidArgumentException("Invalid license '$license'");
        }

        $this->license = $license;
        return $this;
    }

    /** @return string */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @param Author $author
     * @return $this
     */
    public function addAuthor(Author $author)
    {
        $this->authors[] = $author;
        return $this;
    }

    /**
     * @return Author[]
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        if (!$this->getName()) {
            return '';
        }

        list($vendor, $name) = explode('/', $this->getName());
        $vendor = implode('', array_map(
            function ($value) { return ucfirst($value); },
            explode('-', $vendor)
        ));
        $name = implode('', array_map(
            function ($value) { return ucfirst($value); },
            explode('-', $name)
        ));

        return $vendor . '\\' . $name;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'project' => $this,
        ];
    }

    /**
     * @param string $name
     * @return int
     */
    public static function isValidName($name)
    {
        return preg_match('/[a-z\-]*\/[a-z\-]*/', $name);
    }

    /**
     * @return array
     */
    public static function getValidLicenses()
    {
        return self::$validLicenses;
    }

    /**
     * @param bool $license
     * @return bool
     */
    public static function isValidLicense($license)
    {
        return in_array(strtolower($license), self::$validLicenses);
    }

    /**
     * @param string $file
     * @return Project
     */
    public static function fromComposerJson($file)
    {
        Assert::fileExists($file, "File not found: $file");

        $data = json_decode(file_get_contents($file), true);

        $project = new self(
            array_get('name', $data),
            array_get('description', $data),
            array_get('license', $data)
        );

        foreach ($data['authors'] as $author) {
            $project->addAuthor(Author::create(
                array_get('name', $author),
                array_get('email', $author),
                array_get('homepage', $author),
                array_get('role', $author)
            ));
        }

        return $project;
    }
}
