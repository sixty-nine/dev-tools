<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 11/8/17
 * Time: 9:36 AM
 */

namespace SixtyNine\DevTools\Model;


class Vendor
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $email;
    /** @var string */
    protected $namespace;

    /**
     * @param string $email
     * @param string $name
     * @param string $namespace
     */
    public function __construct($email = '', $name = '', $namespace = '')
    {
        $this->email = $email;
        $this->name = $name;
        $this->namespace = $namespace;
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $namespace
     * @return Vendor
     */
    public static function create($email = '', $name = '', $namespace = '')
    {
        return new self($email, $name, $namespace);
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /** @return string */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /** @return string */
    public function getNamespace()
    {
        return $this->namespace;
    }

}