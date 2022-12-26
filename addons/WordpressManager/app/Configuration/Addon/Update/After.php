<?php

namespace ModulesGarden\WordpressManager\App\Configuration\Addon\Update;

use ModulesGarden\WordpressManager\App\Configuration\Addon\Update\Patch\DefaultPatch;
use ModulesGarden\WordpressManager\Core\Helper\DatabaseHelper;

/**
 * Description of After
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class After extends \ModulesGarden\WordpressManager\Core\Configuration\Addon\Update\After
{

    /**
     * @return array
     */
    public function execute(array $params = [])
    {
        $return = parent::execute($params);
        $defaultPath = new DefaultPatch(new DatabaseHelper());
        $defaultPath->up();
        return $return;
    }
}
