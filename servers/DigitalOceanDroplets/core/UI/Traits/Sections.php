<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits;

/**
 * Form Sections Elements related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait Sections
{
    protected $sections = [];

    public function addSection($section)
    {
        $this->sections[$section->getId()] = $section;
        
        return $this;
    }

    public function getSection($id)
    {
        return $this->sections[$id];
    }

    public function getSections()
    {
        return $this->sections;
    }
}
