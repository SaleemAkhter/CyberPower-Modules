<?php

namespace ModulesGarden\Servers\HetznerVps\Core\UI\Traits;

/**
 * View Breadcrumb related functions
 *
 * @author Mariusz Miodowski <mariusz@modulesgarden.com>
 */
trait Toggler
{

    /**
     * @var null
     */
    protected $toggler  = null;

    /**
     * @param int $enable | 0 disabled, 1 enabled, 2 enabled and hidden
     * @return $this
     */
    public function enableToggler($enable = 1)
    {
        $this->toggler  = $enable;

        return $this;
    }

    public function enableTogglerAndHide()
    {
        $this->toggler  = 2;

        return $this;
    }


    /**
     * @return bool
     */
    public function isTogglerEnabled()
    {
        return $this->toggler;
    }

    /**
     * @return bool
     */
    public function isTogglerHidden()
    {
        return $this->toggler === 2;
    }
}