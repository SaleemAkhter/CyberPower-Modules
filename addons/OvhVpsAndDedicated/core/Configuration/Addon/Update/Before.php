<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\Configuration\Addon\Update;

use ModulesGarden\OvhVpsAndDedicated\Core\Configuration\Addon\AbstractBefore;

/**
 * runs after module update actions
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Before extends AbstractBefore
{

    /**
     * @return array
     */
    public function execute($version)
    {
        return [];
    }
}
