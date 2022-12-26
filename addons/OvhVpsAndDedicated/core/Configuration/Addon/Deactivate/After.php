<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\Configuration\Addon\Deactivate;

use ModulesGarden\OvhVpsAndDedicated\Core\Configuration\Addon\AbstractAfter;

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
