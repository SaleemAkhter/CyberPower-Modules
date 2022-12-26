<?php

namespace ModulesGarden\WordpressManager\App\Configuration\Addon\Deactivate;

/**
 * Description of Before
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Before extends \ModulesGarden\WordpressManager\Core\Configuration\Addon\Deactivate\Before
{

    /**
     * @return array
     */
    public function execute()
    {
        $return = parent::execute();

        return $return;
    }
}
