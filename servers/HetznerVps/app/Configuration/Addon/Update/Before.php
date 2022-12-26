<?php

namespace ModulesGarden\Servers\HetznerVps\App\Configuration\Addon\Update;

/**
 * runs before module update actions
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Before extends \ModulesGarden\Servers\HetznerVps\Core\Configuration\Addon\Update\Before
{

    /**
     * @return array
     */
    public function execute($version)
    {
        $return = parent::execute($version);

        return $return;
    }
}
