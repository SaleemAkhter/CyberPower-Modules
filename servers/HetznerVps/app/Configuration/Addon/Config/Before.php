<?php

namespace ModulesGarden\Servers\HetznerVps\App\Configuration\Addon\Config;

/**
 * Runs before loading module configuration
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Before extends \ModulesGarden\Servers\HetznerVps\Core\Configuration\Addon\Config\Before
{

    /**
     * @param array $params
     * @return array
     */
    public function execute(array $params = [])
    {
        $return = parent::execute($params);

        return $return;
    }
}
