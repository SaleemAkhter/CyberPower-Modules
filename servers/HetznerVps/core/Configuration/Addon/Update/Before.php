<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Configuration\Addon\Update;

use ModulesGarden\Servers\HetznerVps\Core\Configuration\Addon\AbstractBefore;

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
