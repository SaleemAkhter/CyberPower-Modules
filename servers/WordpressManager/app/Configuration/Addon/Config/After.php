<?php

namespace ModulesGarden\WordpressManager\App\Configuration\Addon\Config;

/**
 * Description of After
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class After extends \ModulesGarden\WordpressManager\Core\Configuration\Addon\Config\After
{

    /**
     * @return array
     */
    public function execute(array $params = [])
    {
        $return = parent::execute($params);

        return $params;
    }
}
