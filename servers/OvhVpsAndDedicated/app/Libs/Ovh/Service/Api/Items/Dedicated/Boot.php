<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Dedicated\Boot as BootModel;
/**
 * Class Boot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Boot extends AbstractApiItem
{
    public function model()
    {
        return new BootModel($this->getInfo());
    }
}