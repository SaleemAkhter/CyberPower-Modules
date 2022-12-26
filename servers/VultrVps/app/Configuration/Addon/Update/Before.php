<?php

namespace ModulesGarden\Servers\VultrVps\App\Configuration\Addon\Update;

/**
 * runs before module update actions
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Before extends \ModulesGarden\Servers\VultrVps\Core\Configuration\Addon\Update\Before
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
