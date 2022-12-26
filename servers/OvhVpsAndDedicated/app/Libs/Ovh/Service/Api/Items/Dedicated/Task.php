<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Dedicated\Task as TaskModel;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class InstallationTemplate
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Task extends AbstractApiItem
{
    public function model()
    {
        return new TaskModel($this->getInfo());
    }
}
