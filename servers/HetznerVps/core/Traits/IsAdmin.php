<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Traits;

trait IsAdmin
{
    /**
     * @var null|bool
     * determines if run in Admin or Client context
     */
    protected $isAdminStatus = null;

    /**
     * return bool
     */
    public function isAdmin()
    {
        if ($this->isAdminStatus === null)
        {
            $this->isAdminStatus = \ModulesGarden\Servers\HetznerVps\Core\Helper\isAdmin();
        }

        return $this->isAdminStatus;
    }
}
