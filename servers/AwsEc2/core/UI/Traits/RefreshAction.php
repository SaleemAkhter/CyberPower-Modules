<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Traits;

/**
 * Fields related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait RefreshAction
{
    protected $refreshActionIds = [];

    public function getRefreshActionIds()
    {
        return $this->refreshActionIds;
    }

    public function addRefreshActionId($refreshActionId = null)
    {
        $this->refreshActionIds[] = $refreshActionId;

        return $this;
    }
}
