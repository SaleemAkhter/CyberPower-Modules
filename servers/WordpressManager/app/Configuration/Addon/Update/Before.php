<?php

namespace ModulesGarden\WordpressManager\App\Configuration\Addon\Update;

/**
 * Description of Before
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Before extends \ModulesGarden\WordpressManager\Core\Configuration\Addon\Update\Before
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
