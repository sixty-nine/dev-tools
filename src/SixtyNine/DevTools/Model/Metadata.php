<?php

namespace SixtyNine\DevTools\Model;

class Metadata
{
    /** @var Vendor */
    protected $vendor;
    /** @var Project */
    protected $project;

    function __construct(Project $project = null, Vendor $vendor = null)
    {
        $this->project = $project ?: new Project();
        $this->vendor = $vendor ?: new Vendor();
    }

    /**
     * @param \SixtyNine\DevTools\Model\Vendor $vendor
     * @return Metadata
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
        return $this;
    }

    /** @return \SixtyNine\DevTools\Model\Vendor */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param \SixtyNine\DevTools\Model\Project $project
     * @return Metadata
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    /** @return \SixtyNine\DevTools\Model\Project */
    public function getProject()
    {
        return $this->project;
    }

    public function toArray()
    {
        return [
            'vendor' => $this->getVendor(),
            'project' => $this->getProject(),
        ];
    }
}
