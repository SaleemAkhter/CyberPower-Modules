<?php

namespace ModulesGarden\DirectAdminExtended\App\Configuration\Addon\Deactivate;

/**
 * Runs after addon deactivation
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class After extends \ModulesGarden\DirectAdminExtended\Core\Configuration\Addon\Deactivate\After
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
