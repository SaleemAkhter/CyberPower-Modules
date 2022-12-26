<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Configuration\Addon\Update;

/**
 * runs after module update actions
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class After extends \ModulesGarden\Servers\DirectAdminExtended\Core\Configuration\Addon\Update\After
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
