<?php

namespace ModulesGarden\DirectAdminExtended\App\Configuration\Addon\Config;

/**
 * Runs after loading module configuration
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class After extends \ModulesGarden\DirectAdminExtended\Core\Configuration\Addon\Config\After
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
