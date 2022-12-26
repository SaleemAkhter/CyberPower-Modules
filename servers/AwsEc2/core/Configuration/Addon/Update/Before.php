<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Configuration\Addon\Update;

use ModulesGarden\Servers\AwsEc2\Core\Configuration\Addon\AbstractBefore;

/**
 * runs after module update actions
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Before extends AbstractBefore
{

    /**
     * @return array
     */
    public function execute($version)
    {
        return [];
    }
}
