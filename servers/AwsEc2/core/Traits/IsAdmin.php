<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Traits;

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
            $this->isAdminStatus = \ModulesGarden\Servers\AwsEc2\Core\Helper\isAdmin();
        }

        return $this->isAdminStatus;
    }
}
