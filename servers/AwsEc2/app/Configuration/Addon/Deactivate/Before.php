<?php

namespace ModulesGarden\Servers\AwsEc2\App\Configuration\Addon\Deactivate;

/**
 * Runs before addon deactivation
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Before extends \ModulesGarden\Servers\AwsEc2\Core\Configuration\Addon\Deactivate\Before
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
