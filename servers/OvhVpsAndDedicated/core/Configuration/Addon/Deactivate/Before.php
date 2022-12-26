<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\Configuration\Addon\Deactivate;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Configuration\Addon\AbstractBefore;

/**
 * Runs before addon deactivation
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Before extends AbstractBefore
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
