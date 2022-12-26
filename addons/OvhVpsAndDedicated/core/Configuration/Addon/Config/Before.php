<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\Configuration\Addon\Config;

use ModulesGarden\OvhVpsAndDedicated\Core\Configuration\Addon\AbstractBefore;

/**
 * Runs before loading module configuration
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
