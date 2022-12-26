<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Configuration\Addon\Deactivate;

/**
 * Runs before addon deactivation
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Before extends \ModulesGarden\OvhVpsAndDedicated\Core\Configuration\Addon\Deactivate\Before
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
