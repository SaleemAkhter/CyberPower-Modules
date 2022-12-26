<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\Configuration\Addon\Config;

use ModulesGarden\Servers\DirectAdminExtended\Core\Configuration\Addon\AbstractAfter;

/**
 * Runs after loading module configuration
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
