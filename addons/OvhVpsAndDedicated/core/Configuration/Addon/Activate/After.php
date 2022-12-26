<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\Configuration\Addon\Activate;

use ModulesGarden\OvhVpsAndDedicated\Core\Configuration\Addon\AbstractAfter;

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
