<?php

namespace ModulesGarden\Servers\AwsEc2\Core\App\Packages;

abstract class AppPackageConfiguration
{
    const PACKAGE_STATUS = 'packageStatus';
    const PACKAGE_STATUS_ACTIVE = 'active';
    const PACKAGE_STATUS_INACTIVE = 'inactive';

    public function getSuboptionsByCallback($optName = null)
    {
        $fullOptName = $optName . 'GetSubOptions';
        if (method_exists($this, $fullOptName))
        {
            return $this->{$fullOptName}();
        }

        return false;
    }
}
