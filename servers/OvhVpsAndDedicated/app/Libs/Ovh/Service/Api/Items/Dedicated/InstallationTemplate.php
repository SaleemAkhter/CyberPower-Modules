<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Dedicated\InstallationTemplate as InstallationTemplateModel;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts\AbstractApiItem;

/**
 * Class InstallationTemplate
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class InstallationTemplate extends AbstractApiItem
{
    public function model()
    {
        return new InstallationTemplateModel($this->getInfo());
    }
}
