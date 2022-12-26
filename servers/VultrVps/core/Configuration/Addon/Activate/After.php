<?php

namespace ModulesGarden\Servers\VultrVps\Core\Configuration\Addon\Activate;

use ModulesGarden\Servers\VultrVps\Core\Configuration\Addon\AbstractAfter;

/**
 * Runs after module activation actions
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
