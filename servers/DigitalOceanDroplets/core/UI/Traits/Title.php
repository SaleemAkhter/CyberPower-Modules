<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits;
// to do disable title

/**
 * Title elements related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait Title
{
    protected $title = null;
    protected $titleRaw = null;

    public function setTitle($title)
    {
        $this->title = $title;
        
        return $this;
    }

    public function setRawTitle($title)
    {
        $this->titleRaw = $title;
        return $this;
    }

    public function isRawTitle()
    {
        if ($this->titleRaw !== null)
        {
            return true;
        }

        return false;
    }

    public function getRawTitle()
    {
        return $this->titleRaw;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
