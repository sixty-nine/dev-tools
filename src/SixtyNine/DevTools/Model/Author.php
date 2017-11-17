<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 11/8/17
 * Time: 9:36 AM
 */

namespace SixtyNine\DevTools\Model;


class Author
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $email;
    /** @var string */
    protected $homepage;
    /** @var string */
    protected $role;

    function __construct($name, $email = '', $homepage = '', $role = '')
    {
        $this->email = $email;
        $this->homepage = $homepage;
        $this->name = $name;
        $this->role = $role;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $homepage
     * @param string $role
     * @return Author
     */
    public static function create($name, $email = '', $homepage = '', $role = '')
    {
        return new self($name, $email, $homepage, $role);
    }

    /**
     * @param string $email
     * @return Author
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $homepage
     * @return Author
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;
        return $this;
    }

    /**
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * @param string $name
     * @return Author
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $role
     * @return Author
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
}