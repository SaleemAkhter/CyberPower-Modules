<?php

namespace ModulesGarden\DirectAdminExtended\App\Configuration\Addon\Activate;

use ModulesGarden\DirectAdminExtended\App\Services\Migration\Migration;
/**
 * Description of After
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class After extends \ModulesGarden\DirectAdminExtended\Core\Configuration\Addon\Activate\After
{

    /**
     * @param array $params
     * @return array
     */
    public function execute(array $params = [])
    {
        $return = parent::execute($params);

        $this->runMigration();
        return $return;
    }

    private function runMigration(){
        $migrate = new Migration();
        $migrate->run();
    }
}

