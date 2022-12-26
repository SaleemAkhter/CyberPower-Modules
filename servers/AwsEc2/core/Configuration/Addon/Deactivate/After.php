<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Configuration\Addon\Deactivate;

use ModulesGarden\Servers\AwsEc2\Core\Configuration\Addon\AbstractAfter;

/**
 * Runs after addon deactivation
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class After extends AbstractAfter
{

    /**
     * @param array $params
     * @return array
     */
    public function execute(array $params = [])
    {

        return $params;
    }
}
